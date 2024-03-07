<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchCriteriaHandler
{
    public function getSearchCriteria(Request $request, string $param): array
    {
        $allowedParams = $this->getAllowedParams($param);
        
        $requestParameters = $request->query->all();

        $criteria = array_intersect_key($requestParameters, array_flip($allowedParams));
        $invalidParams = array_diff_key($requestParameters, $criteria);

        if (!empty($invalidParams)) {
            return [
                'error' => 'Invalid query parameters: ' . implode(', ', array_keys($invalidParams)),
                'status' => Response::HTTP_BAD_REQUEST
            ];
        }
        return [
            'criteria' => $criteria,
            'status' => Response::HTTP_OK 
        ];
    }

    private function getAllowedParams(string $param): array
    {
        $allowedParamsClass = AllowedParameters::class;
        return constant("$allowedParamsClass::$param");
    }


}
