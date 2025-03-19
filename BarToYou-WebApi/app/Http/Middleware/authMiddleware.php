<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Members;

class authMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Obtener el token de la cabecera de la solicitud
        $token = $request->header('Authorization');

        if (!$token) {
            return response('Token no proporcionado.', 401);
        }

        // 2. Eliminar el prefijo "Bearer " del token (si está presente)
        $token = str_replace('Bearer ', '', $token);

        // Depuración: Mostrar el token recibido
        \Log::info('Token recibido:', ['token' => $token]);

        // 3. Verificar el token en la base de datos
        $member = Members::where('token', $token)->first();

        // Depuración: Mostrar el resultado de la búsqueda
        \Log::info('Resultado de la búsqueda:', ['member' => $member]);

        if (!$member) {
            return response('Token inválido.', 401);
        }

        // 4. Verificar si el token ha expirado
        if (strtotime($member->expiration_date_token) < time()) {
            return response('Token expirado.', 401);
        }

        $request->setUserResolver(fn() => $member);
        
        // 5. Continuar con la solicitud
        return $next($request);
    }
}
