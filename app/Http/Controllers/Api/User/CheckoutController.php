<?php

namespace App\Http\Controllers\Api\User;

use App\Data\MidtransData;
use App\Data\RajaongkirData;
use App\Data\RuleData;
use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\PesananTunggu;
use App\Models\Promo;
use App\Services\JwtService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
	private string $key = 'e93ea205611c296045143ce583b8de62';
	private string $origin = '457';

	function index(Request $request)
	{
		$validator = Validator::make($request->only(['user', 'checkout']), [
			'user' => RuleData::of()->userId()->get(),
			'checkout' => ['required', 'regex:/^[a-zA-Z0-9]+$/i'],
		]);
		if ($validator->fails()) dd($validator->errors());
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		$checkout = PesananTunggu::where(['id_user' => $request->input('user'), 'id_pesanan_tunggu' => $request->input('checkout')])->first();
		if (empty($checkout)) return response()->json(TextData::failed("kosong"), 400);
		$data = [
			'produk' => $checkout->toArray(),
			'bill' => [
				"berat" => collect($checkout->produk)->sum(function ($v) {
					return $v['jumlah'] * $v['berat'];
				}),
				"harga" => collect($checkout->produk)->sum(function ($v) {
					return $v['jumlah'] * $v['harga'];
				}),
				"ongkir" => 0,
				"subtotal" => 0,
				"promo" => 0,
				"total" => 0,
			],
		];
		$data['bill']['subtotal'] = $data['bill']['harga'] + $data['bill']['ongkir'];
		$data['bill']['total'] = $data['bill']['subtotal'] - $data['bill']['promo'];
		return response()->json(TextData::ok($data));
	}

	function store(Request $request)
	{
		$validator = Validator::make($request->only(['checkout', 'kota', 'penerima', 'alamat', 'hp', 'promo', 'kurir', 'paket', "email"]), [
			"email" => ['required', "email:dns"],
			'kota' => ['required', 'numeric', 'min:0'],
			'checkout' => ['required', 'regex:/^[a-zA-Z0-9]+$/i',],
			'penerima' => ['required', 'regex:/^[a-zA-Z0-9\s]+$/i',],
			'alamat' => ['required', 'regex:/^[a-zA-Z0-9\s]+$/i',],
			'hp' => ['required', 'numeric', 'min:0'],
			'promo' => ['nullable', 'regex:/^[a-zA-Z0-9]+$/i',],
			'kurir' => ['required', Rule::in(RajaongkirData::getKurir())],
			'paket' => ['required', 'regex:/^[a-zA-Z0-9\s]+$/i',],
		]);
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		$checkout = PesananTunggu::where(['id_pesanan_tunggu' => $request->input('checkout')])->first();
		if (empty($checkout)) return response()->json(TextData::failed('checkout'), 400);
		$promo = 0;
		if (!empty($request->input('promo'))) {
			$promo = Promo::where('kode', Str::upper($request->input('promo')))->first();
			if (!$promo) return response()->json(TextData::failed(), 400);
			$promo = $promo->status == 'aktif' ? $promo : 0;
			$checkout->promo = $promo->makeHidden(['deskripsi', 'status', 'publish_start', 'publish_end'])->toArray();
		}
		$berat = (int)collect($checkout->produk)->sum(function ($product) {
			return $product['jumlah'] * $product['berat'];
		});
		$rajaongkir = RajaongkirData::getCost($request->input('kota'), $berat, $request->input('kurir'));
		if (!$rajaongkir) return response()->json(TextData::failed('rajaongkir'), 400);
		$ongkir = 0;
		foreach ($rajaongkir['results'][0]['costs'] as $v) {
			if ($v['service'] == $request->input('paket')) {
				$ongkir = $v['cost'][0]['value'];
			}
		}
		$checkout->ongkir = [
			'paket' => $request->input('paket'),
			'nama' => $rajaongkir['results'][0]['name'],
			'harga' => $ongkir,
			'tujuan' => $rajaongkir['destination_details'],
		];
		$checkout->penerima = [
			'penerima' => $request->input('penerima'),
			'alamat' => $request->input('alamat'),
			'hp' => $request->input('hp'),
		];
		if (empty($ongkir)) return response()->json(TextData::failed(), 400);
		$total = collect($checkout->produk)->sum(function ($product) {
			return $product['jumlah'] * $product['harga'];
		});
		$total = $total + $ongkir;
		if (!empty($promo)) {
			$promo = (int)($total * $promo->persen / 100) > $promo->max ? $promo->max : (int)($total * $promo->persen / 100);
		}
		// dd("ok");
		$checkout->promo = $promo;
		if (($total -= $promo) < 10000) return response()->json(TextData::failed('total pembayaran kurang dari Rp 10.000, Silkan hubungi admin'), 400);
		$data = [
			'transaction_details' => [
				'order_id' => $checkout->id_pesanan_tunggu,
				'gross_amount' => $total -= $promo,
			],
			'item_details' => array_merge(collect($checkout->produk)->map(function ($item) {
				return [
					'id' => $item['id_produk'],
					'name' => $item['produk'],
					'quantity' => $item['jumlah'],
					'price' => $item['harga']
				];
			})->toArray(), [[
				'id' => 'ongkir',
				'name' => 'ongkir',
				'quantity' => 1,
				'price' => $ongkir
			]]),
			'customer_details' => [
				'first_name' => $request->input('penerima'),
				'email' => $request->input('email'),
				'phone' => $request->input('hp'),
			],
		];
		if ($promo > 0) {
			array_push($data['item_details'], [
				'id' => 'promo',
				'name' => 'promo',
				'quantity' => 1,
				'price' => -$promo,
			]);
		}

		if (!$snapToken = MidtransData::getToken($data)) return response()->json(TextData::failed(), 400);
		return $checkout->save() ? response()->json(TextData::ok(['token' => $snapToken])) : response()->json(TextData::failed(), 400);
	}

	function promo(Request $request)
	{
		$validator = Validator::make($request->only(['promo']), [
			'promo' => RuleData::of()->promoKode()->get(),
		]);
		if ($validator->fails()) dd($validator->errors());
		if ($validator->fails()) return response()->json(TextData::failed('validation failed'), 400);
		$promo = Promo::where('kode', Str::upper($request->input('promo')))->first();
		return $promo && $promo->status == 'aktif' ? response()->json(TextData::ok(['max' => $promo->max, 'persen' => $promo->persen])) : response()->json(TextData::failed(), 400);
	}

	function tunggu(Request $request)
	{
		$validator = Validator::make($request->only(['user']), [
			'user' => RuleData::of()->userId()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed(), 400);

		$keranjang = Keranjang::with(['produk' => function ($q) {
			$q->select()->publish()->tersedia();
		}])->where('id_user', $request->input('user'))->where('keranjang', '>', 0)->get();
		if (empty($keranjang)) return response()->json(TextData::failed('Not Found'), 400);
		$data = [
			'produk' => [],
			'details' => [
				'keranjang_harga' => 0,
				'keranjang_berat' => 0,
			],
		];
		foreach ($keranjang as $v) {
			if ($v->produk) {
				$harga = $v->keranjang * $v->produk->harga;
				$berat = $v->keranjang * $v->produk->berat;
				array_push($data['produk'], [
					'id_produk' => $v->produk->id_produk,
					'produk' => $v->produk->produk,
					'cover' => $v->produk->cover,
					'jumlah' => $v->keranjang,
					'harga' => $v->produk->harga,
					'berat' => $v->produk->berat,
					'total_harga' =>  $harga,
					'total_berat' =>  $berat,
				]);
				$data['details']['keranjang_harga'] += $harga;
				$data['details']['keranjang_berat'] += $berat;
			}
		}
		$id = (string)Carbon::now()->format('Ymd') . $request->input('user') . STR::random(4);
		$result = PesananTunggu::create([
			'id_pesanan_tunggu' => $id,
			'produk' => $data['produk'],
			'id_user' => $request->input('user')
		]);
		return $result ? response()->json(TextData::ok(['link' => route('view.web.checkout', ['user' => $request->input('user'), 'checkout' => $id])])) : response()->json(TextData::failed(), 400);
	}
}
