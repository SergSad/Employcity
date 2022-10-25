<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\domain\NewsServiceInterface;
use yii\console\Controller;
use yii\console\ExitCode;

class ParsingController extends Controller
{
    private NewsServiceInterface $newsService;

    public function __construct($id, $module, NewsServiceInterface $newsService, $config = [])
    {
        $this->newsService = $newsService;
        parent::__construct($id, $module, $config);
    }

    /**
     * This command parsing news in rbc.ru
     * @return int Exit code
     */
    public function actionRbk()
    {
        $this->newsService->saveNews();

        return ExitCode::OK;
    }
}
