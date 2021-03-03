<?php
/**
 * @date:   2021.02.12
 * @author: Efimkin Evgeny <eep2004@ukr.net>
 **/
namespace app;


/**
 * Class View
 * @package app
 *
 * @property string $sitename
 * @property string $content
 * @property string $message
 * @property array $vars
 */
class View
{
    /** @var string Path to paging */
    const PAGING = '/views/paging.php';

    /** @var string Path to layout */
    const LAYOUT = '/views/layout.php';

    /** @var string Path to template */
    const TEMPLATE = '/views/%s/%s.php';

    private $sitename;
    private $content;
    private $message;
    private $vars;


    /**
     * Render page
     * @param string $route
     * @param string $template
     * @param array $vars
     */
    public function render(string $route, string $template, array $vars = [])
    {
        $this->vars = $vars;

        ob_start();
        include ROOT.sprintf(self::TEMPLATE, $route, $template);
        $this->content = ob_get_clean();

        if (isset($_SESSION['message'])){
            $this->message = $_SESSION['message'];
            unset($_SESSION['message']);
        }

        $this->sitename = Config::get('sitename');
        include ROOT.self::LAYOUT;
    }

    /**
     * Formatted date output
     * @param string $time
     * @return string
     */
    public function date(string $time): string
    {
        $time = strtotime($time);

        $config = Config::get('date');
        $format = !empty($config['format']) ? $config['format'] : 'd.m.Y, H:i';
        if (!empty($config['month'])){
            $month = $config['month'][date('n', $time) - 1];
            $format = str_replace('F', '###', $format);
            $date = str_replace('###', $month, date($format, $time));
        } else {
            $date = date($format, $time);
        }

        return nl2br($date);
    }

    /**
     * Pagination output
     * @param string $root
     * @param int $page
     * @param int $total
     */
    public function paging(string $root, int $page, int $total)
    {
        if ($total < 2 || $total < $page || $page < 1) return;

        $param = strpos($root, '?') !== false ? '&page=' : '?page=';

        $half = Config::get('paging');
        $full = $half * 2 + 1;

        if ($total > $full){
            if ($page < ($half + 1)){
                $beg = 1;
                $end = $full;
            } elseif ($page > ($total - $half)){
                $beg = $total - $full + 1;
                $end = $total;
            } else {
                $beg = $page - $half;
                $end = $page + $half;
            }
        } else {
            $beg = 1;
            $end = $total;
        }

        $list = [];
        if ($page > 1){
            if ($page > ($half + 1) && $total > $full){
                $list[] = [
                    'url' => $root,
                    'name' => '&laquo;',
                ];
            }
            $list[] = [
                'url' => $page > 2 ? $root.$param.($page - 1) : $root,
                'name' => '&lsaquo;',
            ];
        }
        for ($i = $beg; $i <= $end; $i++){
            $list[] = [
                'url' => $i > 1 ? $root.$param.$i : $root,
                'name' => $i,
                'active' => $i == $page ? true : false,
            ];
        }
        if ($page < $total){
            $list[] = [
                'url' => $root.$param.($page + 1),
                'name' => '&rsaquo;',
            ];
            if ($page < ($total - $half) && $total > $full){
                $list[] = [
                    'url' => $root.$param.$total,
                    'name' => '&raquo;',
                ];
            }
        }

        if ($list){
            include ROOT.self::PAGING;
        }
    }

}
