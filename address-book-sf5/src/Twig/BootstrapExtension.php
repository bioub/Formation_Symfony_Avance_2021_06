<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class BootstrapExtension extends AbstractExtension
{
    /** @var RequestStack */
    protected $requestStack;



//    public function getFilters(): array
//    {
//        return [
//            // If your filter generates SAFE HTML, you should add a third
//            // parameter: ['is_safe' => ['html']]
//            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
//            new TwigFilter('filter_name', [$this, 'doSomething']),
//        ];
//    }
    /**
     * BootstrapExtension constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }


    public function getFunctions(): array
    {
        return [
            new TwigFunction('flashAlert', [$this, 'flashAlert'], ['is_safe' => ['html']]),
        ];
    }

    public function flashAlert($type = 'success')
    {
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $messages = $flashBag->get($type);

        $html = '';

        foreach ($messages as $message) {
            $html .= <<<HTML
<div class="alert alert-$type alert-dismissible fade show">
    $message
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
HTML;
        }

       return $html;
    }
}
