<?php

namespace App\Http\Controllers\Config\Lib;

use App\Http\Controllers\Controller;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Http\Request;

class AppInstanceContoller extends Controller
{
    const UNKNOWN = 0;
    const SETTINGS_REQUIRED = 1;
    const ACTIVATED = 100;

    var $appId;
    var $accountId;
    var $infoMessage;
    var $store;

    var $accessToken;

    var $status = AppInstanceContoller::UNKNOWN;

    static function get(): AppInstanceContoller {
        $app = $GLOBALS['currentAppInstance'];
        if (!$app) {
            throw new InvalidArgumentException("There is no current app instance context");
        }
        return $app;
    }

    public function __construct($appId, $accountId)
    {
        $this->appId = $appId;
        $this->accountId = $accountId;
    }

    function getStatusName() {
        switch ($this->status) {
            case self::SETTINGS_REQUIRED:
                return 'SettingsRequired';
            case self::ACTIVATED:
                return 'Activated';
        }
        return null;
    }

    function persist() {
        @mkdir('data');
        file_put_contents($this->filename(), serialize($this));
    }

    function delete() {
        @unlink($this->filename());
    }

    private function filename() {
        return self::buildFilename($this->appId, $this->accountId);
    }

    private static function buildFilename($appId, $accountId) {
        $dir = public_path().'/Config/';
        return $dir . "data/$appId.$accountId.app";
    }

    static function loadApp($appId, $accountId): AppInstanceContoller {
        return self::load($appId, $accountId);
    }

    static function load($appId, $accountId): AppInstanceContoller {
        $data = @file_get_contents(self::buildFilename($appId, $accountId));
        dd($data);
        if ($data === false) {
            $app = new AppInstanceContoller($appId, $accountId);
        } else {
            $app = unserialize($data);
        }
        $_SESSION['currentAppInstance'] = $app;
        return $app;
    }
}