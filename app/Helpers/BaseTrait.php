<?php


namespace App\Helpers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

trait BaseTrait
{
    /**
     *   if your controller inherit this trait, your constructor must be like
     *   public function __construct(SettingService $settingService, SettingRequest $request)
     *   {
     *   $this->service = $settingService;
     *   }
     */

    /**
     * @var
     */
    public $service;

    public function create(Request $request)
    {
        $this->customValidation();
        $service = $this->service->create($request);
        if (!$service) {
            return Response::errorResponse($service);
        }
        return Response::successResponse($service);
    }

    /**
     * get all items
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $service = $this->service->index($request);
        return Response::successResponse($service);
    }

    /**
     * get item details by id
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        $service = $this->service->get($id);
        if (!$service) {
            return Response::errorResponse($service);
        }
        return Response::successResponse($service);
    }

    /**
     * update item using id
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $this->customValidation();
        $service = $this->service->update($request, $id);
        if (!$service) {
            return Response::errorResponse($service);
        }
        return Response::successResponse($service);
    }

    /**
     * delete item
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $this->customValidation();
        $service = $this->service->delete($id);
        if (!$service) {
            return Response::errorResponse($service);
        }
        return Response::successResponse($service);
    }

    public function customValidation()
    {

    }
}
