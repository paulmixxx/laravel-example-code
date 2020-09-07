<?php

namespace App\Http\Controllers\Api\v1\Auth\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateNewTokenRequest;
use Illuminate\Foundation\Auth\RegistersUsers;
use Core\Auth\Commands\Registration\ByEmail\GenerateActivateToken;

class GenerateNewTokenController extends Controller
{
    use RegistersUsers;

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param GenerateNewTokenRequest $request
     * @param GenerateActivateToken\Handler $handler
     * @param GenerateActivateToken\Command $command
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    protected function handle(
        GenerateNewTokenRequest $request,
        GenerateActivateToken\Handler $handler,
        GenerateActivateToken\Command $command
    ) {
        $dto = $request->load($command);

        $handler->handle($dto);

        return response()->json(['success' => true], 200);
    }
}
