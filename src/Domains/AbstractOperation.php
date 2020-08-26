<?php

namespace App\Domains;

use App\Http\Responses\RespondValidationErrorsJson;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use App\Http\Responses\ResponseJsonTrait;

abstract class AbstractOperation implements OperationInterface
{
    use ResponseJsonTrait;

    /**
     *
     * @param string $validatorClass
     * @param array $data
     * @return JsonResponse|null
     */
    protected function validateWithResponse(string $validatorClass, array $data): ?JsonResponse
    {
        $validator = App::build($validatorClass);
        if ($errors = $validator->validateInputFails($data)) {
            return $this->runResponse(new RespondValidationErrorsJson('Błędy walidacji', $errors->toArray()));
        }
        return null;
    }

    /**
     *
     * @param array $keys
     * @param bool $withId
     * @return array
     */
    protected function requestData(array $keys, bool $withId = false): array
    {
        $data = Arr::only(request()->all(), $keys);
        if ($withId) {
            $data = \array_merge($data, ['id' => request()->route('id')]);
        }
        return $data;
    }
}
