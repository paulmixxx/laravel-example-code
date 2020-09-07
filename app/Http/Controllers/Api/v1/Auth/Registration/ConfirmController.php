<?php

namespace App\Http\Controllers\Api\v1\Auth\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationConfirmRequest;
use Core\Auth\Commands\Registration\ByEmail\Confirm;
use Exception;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;

class ConfirmController extends Controller
{
    use RegistersUsers;

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param RegistrationConfirmRequest $request
     * @param Confirm\Handler $handler
     * @param Confirm\Command $command
     * @return JsonResponse
     * @throws Exception
     */
    protected function handle(
        RegistrationConfirmRequest $request,
        Confirm\Handler $handler,
        Confirm\Command $command
    ) {
        $dto = $request->load($command);

        $handler->handle($dto);

        return response()->json(['success' => true], 200);
    }
}
