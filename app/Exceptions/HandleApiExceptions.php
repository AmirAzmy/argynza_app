<?php


namespace App\Exceptions;


use Exception;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class HandleApiExceptions
{
    private $response;

    public function __construct(Exception $exception)
    {
        $this->handleError($exception);
    }

    private function handleError($exception)
    {
        $shortName = lcfirst((new \ReflectionClass($exception))->getShortName());
        if (method_exists($this, $shortName)) {
            $this->$shortName($exception);
            return;
        }
        $this->internalServerError($exception);
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    private function notFoundHttpException()
    {
        $this->response = Response::errorResponse(null, 'Not Found', ResponseStatus::HTTP_NOT_FOUND);
    }

    private function modelNotFoundException()
    {
        $this->response = Response::errorResponse(null, 'Item not Found', ResponseStatus::HTTP_NOT_FOUND);
    }

    private function internalServerError(Exception $exception)
    {
        $message        = $exception->getMessage() . "- FILE:>" . $exception->getFile() . "- LINE:>" . $exception->getLine();
        $this->response = Response::errorResponse(null, $message, ResponseStatus::HTTP_INTERNAL_SERVER_ERROR);
    }

    private function authenticationException(Exception $exception)
    {
        $this->response = Response::errorResponse(null, "Unauthenticated", ResponseStatus::HTTP_UNAUTHORIZED);
    }

    private function validationException(Exception $exception)
    {
        $errors = $exception->validator->errors();
        $data   = [];
        foreach ($errors->toArray() as $field => $error) {
            $data[] = [
                "field" => $field,
                "msg"   => $error[0]
            ];
        }
        $this->response = Response::errorResponse($data, $errors->first(), ResponseStatus::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function badRequestHttpException(Exception $exception)
    {
        $this->response = Response::errorResponse(null, $exception->getMessage());
    }

    private function noPermissionException(Exception $exception)
    {
        $this->response = Response::errorResponse(null, $exception->getMessage(), ResponseStatus::HTTP_FORBIDDEN);
    }

    private function authorizationException(Exception $exception)
    {
        $this->noPermissionException($exception);
    }
}
