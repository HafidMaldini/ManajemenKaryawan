<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckWifiConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    $allowedSSID = 'LAB ACER/5g'; // Ubah sesuai yang benar
    $allowedMac = '64-d1-54-d5-c0-a5';

// Dapatkan SSID & MAC Address
$currentSSID = trim(shell_exec("netsh wlan show interfaces | findstr SSID"));
$currentMac = trim(shell_exec("arp -a | findstr 15.15.0.1"));

// Bersihkan SSID dari karakter tambahan
$currentSSID = preg_replace('/\s*SSID\s*:\s*/', '', $currentSSID);
$currentSSID = preg_replace('/\\\/', '', $currentSSID); // Hilangkan "\"
$currentSSID = trim($currentSSID);

Log::info("Cleaned SSID: " . $currentSSID);
Log::info("Cleaned MAC: " . $currentMac);

if (!str_contains($currentSSID, $allowedSSID) || !str_contains($currentMac, $allowedMac)) {
    return response()->json(['message' => 'Anda tidak terhubung ke jaringan kantor!'], 403);
}

    return $next($request);
}

}
