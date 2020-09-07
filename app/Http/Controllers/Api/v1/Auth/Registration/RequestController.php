<?php

namespace App\Http\Controllers\Api\v1\Auth\Registration;

use App\Http\Requests\RegistrationRequest;
use App\Http\Controllers\Controller;
use Core\Auth\Commands\Registration\ByEmail\Request;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Auth\RegistersUsers;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class RequestController extends Controller
{
    use RegistersUsers;

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param RegistrationRequest $request
     * @param Request\Handler $handler
     * @param Request\Command $command
     * @return ResponseFactory|Response
     * @throws Exception
     */
    protected function handle(
        RegistrationRequest $request,
        Request\Handler $handler,
        Request\Command $command
    ) {
        $dto = $request->load($command);

        $handler->handle($dto);

        return response()->json(['success' => true], 201);
    }
}
