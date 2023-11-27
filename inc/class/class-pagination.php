<?php
class Pagination
{
    /**
     * Biến config chứa tất cả các cấu hình
     *
     * @var array
     */
    private $config = [
        'total' => 0, // tổng số mẩu tin
        'limit' => 0, // số mẩu tin trên một trang
        'full' => true, // true nếu hiện full số page, flase nếu không muốn hiện false
        'mark' => '', // hiển thị dấu ?
        'querystring' => 'page', // GET id nhận page
        'url' => '' // url
    ];

    /**
     * khởi tạo
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        // kiểm tra xem trong config có limit, total đủ điều kiện không
        if (isset($config['limit']) && $config['limit'] < 0 || isset($config['total']) && $config['total'] < 0) {
            // nếu không thì dừng chương trình và hiển thị thông báo.
            die('limit và total không được nhỏ hơn 0');
        }
        // Kiểm tra xem config có querystring không
        if (!isset($config['querystring'])) {
            //nếu không để mặc định là page
            $config['querystring'] = 'page';
        }
        $this->config = $config;
    }

    /**
     * Lấy ra tổng số trang
     *
     * @return int
     */
    private function gettotalPage()
    {
        return ceil($this->config['total'] / $this->config['limit']);
    }

    /**
     * Lấy ra trang hiện tại
     *
     * @return int
     */
    private function getCurrentPage()
    {
        // kiểm tra tồn tại GET querystring và có >=1 không
        if (isset($_GET[$this->config['querystring']]) && (int)$_GET[$this->config['querystring']] >= 1) {
            // Nếu có kiểm tra tiếp xem nó có lớn hơn tổn số trang không.
            if ((int)$_GET[$this->config['querystring']] > $this->gettotalPage()) {
                // nếu lớn hơn thì trả về tổng số page
                return (int)$this->gettotalPage();
            } else {
                // còn không thì trả về số trang
                return (int)$_GET[$this->config['querystring']];
            }

        } else {
            // nếu không có querystring thì nhận mặc định là 1
            return 1;
        }
    }

    /**
     * lấy ra trang phía trước
     *
     * @return string
     */
    private function getPrePage()
    {
        // nếu trang hiện tại bằng 1 thì trả về null
        if ($this->getCurrentPage() === 1) {
            return;
        } 
        elseif ($this->getCurrentPage() === 2) {
            return '<li><a href="' . $this->config['url'] .'" >«</a></li>';
        }
        else {
            // còn không thì trả về html code
            return '<li><a href="' . $this->config['url'] . $this->config['mark'] . $this->config['querystring'] . '=' . ($this->getCurrentPage() - 1) . '" >«</a></li>';
        }
    }

    /**
     * Lấy ra trang phía sau
     *
     * @return string
     */
    private function getNextPage()
    {
        // nếu trang hiện tại lơn hơn = totalpage thì trả về rỗng
        if ($this->getCurrentPage() >= $this->gettotalPage()) {
            return;
        } else {
            // còn không thì trả về HTML code
            return '<li><a href="' . $this->config['url'] . $this->config['mark'] . $this->config['querystring'] . '=' . ($this->getCurrentPage() + 1) . '" >»</a></li>';
        }
    }

    /**
     * Hiển thị html code của page
     *
     * @return string
     */
    public function getPagination()
    {
        // tạo biến data rỗng
        $data = '';
        // kiểm tra xem người dùng có cần full page không.
        if (isset($this->config['full']) && $this->config['full'] === false) {
            // nếu không thì
            $data .= ($this->getCurrentPage() - 3) > 1 ? '<li>...</li>' : '';

            for ($i = ($this->getCurrentPage() - 3) > 0 ? ($this->getCurrentPage() - 3) : 1; $i <= (($this->getCurrentPage() + 3) > $this->gettotalPage() ? $this->gettotalPage() : ($this->getCurrentPage() + 3)); $i++) {
                if ($i === $this->getCurrentPage()) {
                    $data .= '<li class="active" ><a href="#" >' . $i . '</a></li>';
                } else {
                    if ($i == 1) {
                        $data .= '<li><a href="' . $this->config['url'] .'" >' . $i . '</a></li>';    
                    } 
                    else {
                        $data .= '<li><a href="' . $this->config['url'] . $this->config['mark'] . $this->config['querystring'] . '=' . $i . '" >' . $i . '</a></li>';
                    }
                    
                }
            }

            $data .= ($this->getCurrentPage() + 3) < $this->gettotalPage() ? '<li>...</li>' : '';
        } else {
            // nếu có thì
            for ($i = 1; $i <= $this->gettotalPage(); $i++) {
                if ($i === $this->getCurrentPage()) {
                    $data .= '<li class="active" ><a href="#" >' . $i . '</a></li>';
                } else {
                    if ($i == 1) {
                        $data .= '<li><a href="' . $this->config['url'] . '" >' . $i . '</a></li>';
                    } 
                    else {
                        $data .= '<li><a href="' . $this->config['url'] . $this->config['mark'] . $this->config['querystring'] . '=' . $i . '" >' . $i . '</a></li>';
                    }
                }
            }
        }

        return '<ul class="flex clear-list mb-0">' . $this->getPrePage() . $data . $this->getNextPage() . '</ul>';
    }
}