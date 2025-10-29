<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

// custom stuff
use App\Models\SettingsModel;
use App\Repositories\PageContentRepository;
use App\Repositories\NavigationRepository;
use App\Libraries\MenuBuilder;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;

    /**
     * @return void
     */
     
    // custom properties
    protected $viewData = [];
    
    // custom methods
    protected function renderMenus(string $url): void {
        $menuConfig = config('Menus');
        $this->menuModel = new NavigationRepository();
        $menus = $this->menuModel->getMenus();
        $builder = new MenuBuilder();
        foreach ( $menus as $name=>$menu ) {
            $builder->setItems($menu->items)->setClasses($menuConfig->menuClasses[$name])->setActive($url);
            $this->viewData[$name] = $builder->render();
        }
    }
    
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // page data
        $url = $this->request->getUri()->getPath(); 
        $url = '/' . ltrim($url, '/'); 
        $this->pageRepo = new PageContentRepository();
        $page = $this->pageRepo->pageDataByUrl($url);
        if (is_null($page)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($url);
        }
        $this->viewData = $page;
        // settings
        $this->settingsModel = new SettingsModel();
        $this->viewData += $this->settingsModel->getSettings();
        // session
        $this->session = \Config\Services::session();
        // menus
        $this->renderMenus($url);
    }
}
