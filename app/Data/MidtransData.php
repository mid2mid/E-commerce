<?php

namespace App\Data;

class MidtransData
{
	// S A N D B O X
	private static string $sandboxClientKey = '';
	private static string $sandboxIdMerchant = '';
	private static string $sandboxServerKey = "";
	private static string $sandboxSnap = "https://app.sandbox.midtrans.com/snap/snap.js";
	// P R O D U C T I O N
	private static string $productionClientKey = '';
	private static string $productionIdMerchant = '';
	private static string $productionServerKey = "";
	private static string $productionSnap = "https://app.midtrans.com/snap/snap.js";

	private static function getEnv(): string
	{
		return env("MIDTRANS_ENV", "sandbox");
	}

	static function getClientKey(): string
	{
		return self::getEnv() === "production" ? self::$productionClientKey :  self::$sandboxClientKey;
	}

	static function getIdMerchant(): string
	{
		return self::getEnv() === "production" ? self::$productionIdMerchant :  self::$sandboxIdMerchant;
	}

	static function getServerKey(): string
	{
		return self::getEnv() === "production" ? self::$productionServerKey :  self::$sandboxServerKey;
	}

	static function getSnap(): string
	{
		return self::getEnv() === "production" ? self::$productionSnap :  self::$sandboxSnap;
	}

	static function getSrc(): string
	{
		return self::getEnv() === "production" ? self::$productionServerKey :  self::$sandboxServerKey;
	}

	private static function isProduction()
	{
		\Midtrans\Config::$serverKey = self::getServerKey();
		\Midtrans\Config::$isProduction = true;
		\Midtrans\Config::$isSanitized = true;
		\Midtrans\Config::$is3ds = true;
	}
	private static function isSandbox()
	{
		\Midtrans\Config::$serverKey = self::getServerKey();
		\Midtrans\Config::$isProduction = false;
		\Midtrans\Config::$isSanitized = true;
		\Midtrans\Config::$is3ds = true;
	}

	static function getToken(array $data): string|null
	{
		self::getEnv() === "production" ? self::isProduction() : self::isSandbox();
		try {
			return \Midtrans\Snap::getSnapToken($data);
		} catch (\Throwable $th) {
			dd($th);
			return null;
		}
	}
}
