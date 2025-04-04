<?php

namespace App\Http\Controllers;

use App\Http\Requests\RfidInsertRequest;
use App\Repositories\RfidHeartbeatRepository;
use App\Repositories\RfidLogRepository;
use App\Repositories\RfidTagReadRepository;

class RfidApiController extends Controller
{
    private RfidTagReadRepository $rfidTagReadRepository;
    private RfidHeartbeatRepository $rfidHeartbeatRepository;
    private RfidLogRepository $rfidLogRepository;

    public function __construct(
        RfidTagReadRepository $rfidTagReadRepository,
        RfidHeartbeatRepository $rfidHeartbeatRepository,
        RfidLogRepository $rfidLogRepository
    )
    {
        $this->rfidTagReadRepository = $rfidTagReadRepository;
        $this->rfidHeartbeatRepository = $rfidHeartbeatRepository;
        $this->rfidLogRepository = $rfidLogRepository;
    }

    public function insert(RfidInsertRequest $request)
    {
        $input = $request->all();

        if ($request->get('event_type') == 'tag_read') {
            $messages = $this->rfidTagReadRepository->insertTagRead($input);
        }
        if ($request->get('event_type') == 'heart_beat') {
            $messages = $this->rfidHeartbeatRepository->insertHeartBeat($input);
        }

        if ($request->get('event_type') == 'sync_time_req') {
            $messages['command_type'] = 'sync_time';
            $messages['command_data'] = time() * 1000;
        }

        if (isset($messages['error'])) {
            $messages['code'] = 422;
            $messages['status'] = 'error';
            $this->rfidLogRepository->insertInboundLog([
                'payload' => json_encode($request->all()),
                'error_message' => json_encode($messages)
            ]);
        } else {
                if($request->get('event_type') == 'sync_time_req'){
                }
                else{
                    $messages['code'] = 200;
                    $messages['status'] = 'success';
                }
        }

        return response($messages);
    }


}
