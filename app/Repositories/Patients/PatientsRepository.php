<?php

namespace App\Repositories\Patients;

use App\Patient;
use App\Repositories\BaseRepository;

class PatientsRepository extends BaseRepository implements PatientsRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Patient::Query());
    }

    public function list($with_get = true, array $param = [])
    {
        $first_name = $param["first_name"] ?? null;
        $last_name = $param["last_name"] ?? null;
        $email = $param["email"] ?? null;
        $status = $param["status"] ?? null;

        $patients = $this->query();

        if ($first_name) {
            $patients = $patients->where('first_name', 'like', '%' . $first_name . '%');
        }
        if ($last_name) {
            $patients = $patients->where('last_name', 'like', '%' . $last_name . '%');
        }
        if ($email) {
            $patients = $patients->where('email', 'like', '%' . $email . '%');
        }
        if ($status == '1' || $status == '0') {
            $patients = $patients->where('is_active', '=', $status);
        }

        if ($with_get) {
            $patients = $patients->get();
        }
        return $patients;
    }

}
