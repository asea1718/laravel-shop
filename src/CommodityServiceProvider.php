<?php
namespace SimpleShop\Commodity;

use App\Services\Tpl\Commodity\Detail\Attr;
use App\Services\Tpl\Commodity\Detail\Content;
use App\Services\Tpl\Commodity\Detail\Crumb;
use App\Services\Tpl\Commodity\Detail\Image;
use App\Services\Tpl\Commodity\Detail\Params;
use App\Services\Tpl\Commodity\Index\Screening;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container as Application;
use Illuminate\Foundation\Application as LaravelApplication;
use SimpleShop\Commodity\Search\RepositoryInterface;
use SimpleShop\Commodity\Search\SearchRepository;
use Illuminate\Support\Facades\Blade;
use SimpleShop\Commodity\Sku;

class CommodityServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;


	/**
	 * Boot the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->setupConfig($this->app);
		$this->setupMigrations($this->app);
        $this->loadViewsFrom(dirname(__FILE__) . '/Views', 'Goods');
        $this->bootProduct();
        $this->bootImage();
        $this->bootParams();
        $this->bootAttr();
        $this->bootContent();
        $this->bootScreening();
	}

	/**
	 * 初始化配置
	 *
	 * @param \Illuminate\Contracts\Container\Container $app
	 *
	 * @return void
	 */
	protected function setupConfig(Application $app)
	{
		$source = realpath(__DIR__.'/../config/commodity.php');

		if ($app instanceof LaravelApplication && $app->runningInConsole()) {
			$this->publishes([$source => config_path('commodity.php')]);
		} elseif ($app instanceof LumenApplication) {
			$app->configure('commodity');
		}

		$this->mergeConfigFrom($source, 'commodity');
	}

	/**
	 * 初始化数据库
	 *
	 * @param \Illuminate\Contracts\Container\Container $app
	 *
	 * @return void
	 */
	protected function setupMigrations(Application $app)
	{
		$source = realpath(__DIR__.'/../database/migrations/');

		if ($app instanceof LaravelApplication && $app->runningInConsole()) {
			$this->publishes([$source => database_path('migrations')], 'migrations');
		}
	}

    /**
     *
     */
    public function bootProduct()
    {
        Blade::directive('crumb', function ($expression) {
            return Crumb::html($expression);
        });
	}

    public function bootImage()
    {
        Blade::directive('image', function ($expression) {
            return Image::html($expression);
        });
	}

    public function bootParams()
    {
        Blade::directive('params', function ($expression) {
            return Params::html($expression);
        });
    }

    public function bootAttr()
    {
        Blade::directive('attr', function ($expression) {
            return Attr::html($expression);
        });
    }

    public function bootContent()
    {
        Blade::directive('content', function ($expression) {
            return Content::html($expression);
        });
    }

    public function bootScreening()
    {
        Blade::directive('screening', function () {
            return Screening::html();
        });
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

        Blade::directive('shoplist', function($expression) {
//            $params = collect(explode(',', $expression))->map(function ($item) {
//                return trim($item);
//            });
//            $params->get(0)；
//            $data = app(\SimpleShop\Commodity\Commodity::class)->search(['not_ids'=>[1]]);
            return
                "<?php foreach (app(\SimpleShop\Commodity\Commodity::class)->search(['not_ids'=>[1]]) as \$key=>\$item) : ?>";

        });
        Blade::directive('endshoplist', function($expression) {
            return "<?php endforeach; ?>";

        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
