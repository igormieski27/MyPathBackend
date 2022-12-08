<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Http\Request;

class BaseService
{
    protected ?Usuario $usuario;
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = request();
        
        $this->usuario = $request->user();
    }

    /**
     * Retorna o $contet e $statusCode
     */
    protected function response($content, $statusCode = 200): array
    {
        $key = $statusCode >= 200 && $statusCode < 300 ? 'mensagem' : 'error';

        if (is_string($content)) {
            $content = [
                $key => $content
            ];
        }

        return [
            'content' => $content,
            'statusCode' => $statusCode
        ];
    }

    /**
     * Retorna a resposta do tipo success (200)
     */
    protected function responseSuccess($content): array
    {
        return $this->response($content, 200);
    }

    /**
     * Retorna a resposta do tipo Created (201)
     */
    protected function responseCreated($content): array
    {
        return $this->response($content, 201);
    }

    /**
     * Retorna a resposta do tipo Failure (400)
     */
    protected function responseFailure($content): array
    {
        return $this->response($content, 400);
    }

    /**
     * Retorna a resposta do tipo NotFound (404)
     */
    protected function responseNotFound($content): array
    {
        return $this->response($content, 404);
    }

    /**
     * Retorna a resposta do tipo NotAcceptable (200)
     */
    protected function responseNotAcceptable($content): array
    {
        return $this->response($content, 406);
    }
}