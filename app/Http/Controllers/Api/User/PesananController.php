<?php

namespace App\Http\Controllers\Api\User;

use App\Data\RuleData;
use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Penjualan;
use App\Models\Pesanan;
use App\Models\PesananTunggu;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class PesananController extends Controller
{
	function index(Request $request)
	{
		$validator = Validator::make($request->only(['user']), [
			'user' => RuleData::of()->userId()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		$pesanan = Pesanan::where(['id_user' => $request->input('user'), 'status' => 'settlement'])->orWhere('status', 'pending')->orderBy('created_at', 'desc')->get();
		if (empty($pesanan)) return response()->json(TextData::failed(), 400);
		$data = collect($pesanan->toArray())->map(function ($v) {
			$total = collect($v['produk'])->sum(function ($product) {
				return $product['jumlah'] * $product['harga'];
			});
			$total = ($total + $v['ongkir']['harga']) - $v['promo'];
			return [
				'id_pesanan' => $v['id_pesanan'],
				'status' => $v['status'],
				'resi' => $v['resi'],
				'payment_code' => $v['payment_code'],
				'total' => $total,
				"settlement_time" => strtotime($v['settlement_time'])
			];
		})->toArray();
		return response()->json(TextData::ok($data));
	}
	function show(Pesanan $pesanan)
	{
		$pesanan = $pesanan->toArray();
		$pesanan['settlement_time'] = strtotime($pesanan['settlement_time']);
		return response()->json(TextData::ok($pesanan));
	}

	function store(Request $request, Pesanan $pm)
	{
		$validator = Validator::make($request->only(['order_id', 'status_code', 'gross_amount', 'transaction_id', 'transaction_time', 'payment_type', 'transaction_status', 'payment_code']), [
			'order_id' => ['required'],
			'status_code' => ['required'],
			'gross_amount' => ['required'],
			'transaction_id' => ['required'],
			'transaction_status' => ['required'],
			'transaction_time' => ['required'],
			'payment_type' => ['required'],
			'payment_code' => ['nullable'],
		]);
		if ($validator->fails()) return response()->json(TextData::failed(), 400);

		$pesanan = Pesanan::where('id_pesanan', $request->input('order_id'))->first();
		$checkout = PesananTunggu::where('id_pesanan_tunggu', $request->input('order_id'))->first();
		if (empty($pesanan)) {
			if (empty($checkout)) return response()->json(TextData::failed(), 400);
		}
		$data = [
			'id_pesanan' => $checkout->id_pesanan_tunggu,
			'id_user' => $checkout->id_user,
			'penerima' => $checkout->penerima,
			'produk' => $checkout->produk,
			'promo' => $checkout->promo,
			'ongkir' => $checkout->ongkir,
			'midtrans' => [
				"transaction_id" => $request->input('transaction_id'),
			],
			'status' => $request->input('transaction_status'),
			"gross_amount" => (int)$request->input('gross_amount'),
		];
		if ($request->input('payment_type') == "gopay") {
			$data['metode'] = "gopay";
			$data['payment_code'] = null;
		} else if ($request->input('payment_type') == "qris") {
			$data['metode'] = $request->input('payment_type');
			$data['payment_code'] = null;
		} else if ($request->input('payment_type') == "bank_transfer") {
			if (isset($request->input("va_numbers"))) {
				$data['metode'] = $request->input("va_numbers")['bank'];
				$data['payment_code'] = $request->input("va_numbers")['va_number'];
			}
		} else if ($request->input('payment_type') == "cstore") {
			$data['metode'] = $request->input("store");
			$data['payment_code'] = $request->input("payment_code");
		} else {
			$data['metode'] = $request->input('payment_type');
			$data['payment_code'] = null;
		}
		if ($request->input('transaction_status') === "settlement") {
			$data['settlement_time'] = $request->input('settlement_time');
		} else {
			$data['settlement_time'] = null;
		}

		$user = $data['id_user'];
		$id_pesanan = $data['id_pesanan'];
		$penjualan = collect($checkout->produk)->map(function ($v) use ($user, $id_pesanan) {
			return ['id_Produk' => $v['id_produk'], "id_user" => $user, "jumlah" => $v['jumlah'], "id_pesanan" => $id_pesanan, "produk" => $v['produk']];
		})->toArray();
		$status = $request->input('transaction_status');
		if ($status === "settlement") {
			if (!empty($pesanan)) {
				try {
					Pesanan::where("id_pesanan", $data['id_pesanan'])->update($data);
					if ($pesanan->status !== "settlement") {
						Penjualan::insert($penjualan);
					}
					DB::commit();
					return response()->json(TextData::ok());
				} catch (\Throwable $th) {
					DB::rollBack();
					return response()->json(TextData::failed());
				}
			}
			$produk = Produk::select();
			foreach ($checkout->produk as $v) {
				$produk = $produk->orWhere(['id_produk' => $v['id_produk']]);
			}
			$produk = $produk->tersedia()->publish()->get()->toArray();
			$dum = $checkout->produk;
			$produkUpdate =
				collect($produk)->map(function ($item, $key) use ($dum) {
					foreach ($dum as $i => $v) {
						if ($item['id_produk'] == $v['id_produk']) {
							$item['jumlah'] = $item['jumlah'] - $v['jumlah'] > 0 ? $item['jumlah'] - $v['jumlah'] : 0;
							return $item;
						}
					}
				})->toArray();
			$keranjang = Keranjang::select();
			collect($produk)->map(function ($item) use ($keranjang) {
				$keranjang = $keranjang->orWhere(['id_produk' => $item['id_produk']]);
			});
			$keranjang = $keranjang->get()->toArray();
			$keranjangUpdate =
				collect($keranjang)->map(function ($item, $key) use ($produk) {
					foreach ($produk as $i => $v) {
						if ($item['id_produk'] == $v['id_produk']) {
							$item['keranjang'] = $item['keranjang'] - $v['jumlah']  > 0 ? $item['keranjang'] - $v['jumlah'] : 0;
							return $item;
						}
					}
				})->toArray();
			try {
				DB::beginTransaction();
				foreach ($produkUpdate as $v) {
					Produk::where(['id_produk' => $v['id_produk']])->publish()->update(['jumlah' => $v['jumlah']]);
				}
				foreach ($keranjangUpdate as $v) {
					Keranjang::where(['id_produk' => $v['id_produk'], 'id_user' => $v['id_user']])->update(['keranjang' => $v['keranjang']]);
				}
				Keranjang::where('keranjang', '<', 1)->delete();
				Pesanan::create($data);
				Penjualan::insert($penjualan);
				DB::commit();
				return  response()->json(TextData::ok());
			} catch (\Throwable $th) {
				DB::rollBack();
				dd($th);
				return response()->json(TextData::failed());
			}
		} else if ($status === "pending") {
			if (!empty($pesanan)) {
				try {
					Pesanan::where("id_pesanan", $data['id_pesanan'])->update($data);
					Penjualan::where("id_pesanan", $data['id_pesanan'])->delete();
					DB::commit();
					return response()->json(TextData::ok());
				} catch (\Throwable $th) {
					DB::rollBack();
					return response()->json(TextData::failed());
				}
			}
			$produk = Produk::select();
			foreach ($checkout->produk as $v) {
				$produk = $produk->orWhere(['id_produk' => $v['id_produk']]);
			}
			$produk = $produk->tersedia()->publish()->get()->toArray();
			$dum = $checkout->produk;
			$produkUpdate =
				collect($produk)->map(function ($item, $key) use ($dum) {
					foreach ($dum as $i => $v) {
						if ($item['id_produk'] == $v['id_produk']) {
							$item['jumlah'] = $item['jumlah'] - $v['jumlah'] > 0 ? $item['jumlah'] - $v['jumlah'] : 0;
							return $item;
						}
					}
				})->toArray();
			$keranjang = Keranjang::select();
			collect($produk)->map(function ($item) use ($keranjang) {
				$keranjang = $keranjang->orWhere(['id_produk' => $item['id_produk']]);
			});
			$keranjang = $keranjang->get()->toArray();
			$keranjangUpdate =
				collect($keranjang)->map(function ($item, $key) use ($produk) {
					foreach ($produk as $i => $v) {
						if ($item['id_produk'] == $v['id_produk']) {
							$item['keranjang'] = $item['keranjang'] - $v['jumlah']  > 0 ? $item['keranjang'] - $v['jumlah'] : 0;
							return $item;
						}
					}
				})->toArray();
			try {
				DB::beginTransaction();
				foreach ($produkUpdate as $v) {
					Produk::where(['id_produk' => $v['id_produk']])->publish()->update(['jumlah' => $v['jumlah']]);
				}
				foreach ($keranjangUpdate as $v) {
					Keranjang::where(['id_produk' => $v['id_produk'], 'id_user' => $v['id_user']])->update(['keranjang' => $v['keranjang']]);
				}
				if ($status === "settlement") {
					Penjualan::create($penjualan);
				}
				Keranjang::where('keranjang', '<', 1)->delete();
				Pesanan::create($data);
				DB::commit();
				return  response()->json(TextData::ok());
			} catch (\Throwable $th) {
				DB::rollBack();
				return response()->json(TextData::failed());
			}
		} else {
			if (!empty($pesanan)) {
				if ($pesanan->status === "settlement" || $pesanan->status === "pending") {
					$produk = Produk::select();
					foreach ($checkout->produk as $v) {
						$produk = $produk->orWhere(['id_produk' => $v['id_produk']]);
					}
					$produk = $produk->tersedia()->publish()->get()->toArray();
					$dum = $checkout->produk;
					$produkUpdate =
						collect($produk)->map(function ($item, $key) use ($dum) {
							foreach ($dum as $i => $v) {
								if ($item['id_produk'] == $v['id_produk']) {
									$item['jumlah'] = $item['jumlah'] + $v['jumlah'] > 0 ? $item['jumlah'] + $v['jumlah'] : 0;
									return $item;
								}
							}
						})->toArray();
					try {
						DB::beginTransaction();
						foreach ($produkUpdate as $v) {
							Produk::where(['id_produk' => $v['id_produk']])->publish()->update(['jumlah' => $v['jumlah']]);
						}
						if ($pesanan->status === "settlement") {
							Penjualan::where("id_pesanan", $id_pesanan)->delete();
						}
						$pesanan->status = $data['status'];
						$pesanan->settlement_time = null;
						$pesanan->save();
						Penjualan::where("id_pesanan", $data['id_pesanan'])->delete();
						DB::commit();
						return  response()->json(TextData::ok());
					} catch (\Throwable $th) {
						DB::rollBack();
						return  response()->json(TextData::failed());
					}
				} else {
					try {
						$pesanan->status = $data['status'];
						$pesanan->settlement_time = null;
						$pesanan->save();
						Penjualan::where("id_pesanan", $data['id_pesanan'])->delete();
						DB::commit();
						return response()->json(TextData::ok());
					} catch (\Throwable $th) {
						DB::rollBack();
						return response()->json(TextData::failed());
					}
				}
			} else {
				return Pesanan::create($data) ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
			}
		}
	}

	function produk(Pesanan $pesanan)
	{
		return response()->json(TextData::ok($pesanan->produk));
	}
}
