<?php
declare(strict_types=1);

/**
* Created by : Gbenga Ogunbule
* Location : Ijebu Ode
* Date : 27/05/20
* Time : 21:01
*/

namespace App\Form;

class Form
{
    protected $err_flds;
    protected $vals;
    
    public function start($vals = null, $action = null)
    {
        $this->err_flds = array();
        $this->vals = $vals;
        if (is_null($action)) {
            $action = "";
        }
        echo "<form action='$action' method=post accept-charset=UTF-8>";
        if (isset($_SESSION['csrftoken'])) {
            $this->hidden('csrftoken', $_SESSION['csrftoken']);
        }
    }

    public function end()
    {
        echo "</form>";
    }
    
    public function hidden($fld, $v)
    {
        $v = htmlspecialchars($v);
        echo "<input id=$fld type=hidden name=$fld value='$v'>";
    }
    
    public function errors($err_flds)
    {
        $this->err_flds = $err_flds;
    }

    public function text($fld, $label = null, $len = 50, $placeholder = '', $break = true, $password = false)
    {
        if ($password) {
            $type = 'password';
        } else {
            $type = 'text';
        }
        $this->label($fld, $label, $break);
        
        //$v = isset($this->vals[$fld]) ? htmlspecial($this->vals[$fld]) : '';
        $v = htmlspecial($this->vals[$fld]) ?? '';
        echo "<input id=$fld type=$type size=$len name=$fld value='$v' placeholder='$placeholder'>";
    }
    
    public function textarea($fld, $label = null, $cols = 100, $rows = 5, $readonly = false)
    {
        $this->label($fld, $label, true);
        //$v = isset($this->vals[$fld]) ? htmlspecial($this->vals[$fld]) : '';
        $v = htmlspecial($this->vals[$fld]) ?? '';
        echo "<br><textarea id=$fld name=$fld cols=$cols rows=$rows>$v</textarea>";
    }

    public function label($fld, $label, $break)
    {
        if (is_null($label)) {
            $label = $fld;
        }
        if ($break) {
            echo '<p class=label>';
        } else {
            $this->hspace();
        }
        $st = isset($this->err_flds[$fld]) ? 'style="color:red;"' : '';
        echo "<label class=label for=$fld $st>$label</label>";
    }

    public function button($fld, $label = null, $break = true)
    {
        if ($break) {
            echo '<p class=label>';
        }
        echo "<input id=$fld class=button type=submit name=$fld value='$label'>";
    }

    public function hspace($ems = 1)
    {
        echo "<span style='margin-left:{$ems}em;'></span>";
    }

    public function foreign_key($fldfk, $fldvis, $label = null, $len = 50)
    {
        $vfk = isset($this->vals[$fldfk]) ? $this->vals[$fldfk] : '';
        $this->hidden($fldfk, $vfk);
        $fld = "{$fldfk}_label";
        $this->label($fld, $label, true);
        //$v = isset($this->vals[$fldvis]) ? htmlspecial($this->vals[$fldvis]) : '';
        $v = htmlspecial($this->vals[$fldvis]) ?? '';
        echo "<input id=$fld type=text size=$len name=$fld value='$v' readonly>";
        echo "<button class=button type=button onclick='ChooseSpecialty(\"$fldfk\");'>Choose...</button>";
        echo "<button class=button type=button onclick='ClearField(\"$fldfk\");'>Clear</button>";
    }

    public function checkbox($fld, $label, $break = true)
    {
        $this->label($fld, $label, $break);
        $checked = (empty($this->vals[$fld]) || $this->vals[$fld] === '0') ? '' : 'checked';
        echo "<input id=$fld type=checkbox name=$fld value=1 $checked>";
    }

    public function radio($fld, $label, $value, $break = true)
    {
        if ($break) {
            echo '<p class=label>';
        }
        $st = isset($this->err_flds[$fld]) && $this->err_flds[$fld] == $value ? 'style="color:red;"' : '';
        $checked = isset($this->vals[$fld]) && $this->vals[$fld] == $value ? 'checked' : '';
        echo <<<EOT
        <input type=radio name=$fld value='$value' $checked>
        <label class=label for=$fld $st>$label</label>
EOT;
    }

    public function menu($fld, $label, $values, $break = true, $default = null)
    {
        $this->label($fld, $label, $break);
        echo "<select id=$fld name=$fld>";
        echo "<option value=''></option>";
        if (isset($this->vals[$fld])) {
            $curval = $this->vals[$fld];
        } else {
            $curval = $default;
        }
        foreach ($values as $v) {
            echo "<option value='$v' " . ($curval == $v ? "selected" : "") . ">$v</option>";
        }
        echo "</select>";
    }

    public function date($fld, $label, $break = true)
    {
        $this->text($fld, $label, 10, 'YYYY-MM-DD', $break);
        echo <<<EOT
        <script>
            $(document).ready(function() {
                $('#$fld').datepicker({dateFormat: 'yy-mm-dd'});
            });
        </script>
EOT;
    }

    public function passwordStrength($fld, $userid)
    {
        echo '<span id="password-strength></span>';
        echo <<<EOT
        <script>
            $('#$fld').bind('keydown', function(){
                PasswordDidi=Change('$fld', '$userid');
            });
        </script>
EOT;
    }

    /*public function PasswordDidChange(id, username) {
        $('#password-strength'). html(passwordStrength($('#' + id).val(), username));
    }

    function setCookie(name, value, expires, path, domain, secure) {
        let today = new Date();
        today.setTime(today.getTime());
        if (expires)
            expires = expires * 1000 * 60 * 60 * 24;
        let date = new Date(today.getTime() + (expires));
        document.cookie = name + '=' + escape(value) + ((expires) ? ';expires=' + date.toGMTString() : '') +
        ((path) ? ';path=' + path : '') + ((domain) ? ';domain=' + domain : '') + ((secure) ? ';secure' : '');
    }

    function browser_signature(url, params) {
var div = document.createElement('div');
div.setAttribute('id', 'inch');
div.setAttribute('style',
'width:1in;height:1in;position:absolute');
var t = document.createTextNode(' '); // might be needed
div.appendChild(t);
document.body.appendChild(div);
var x = navigator.userAgent + '-';
x += document.getElementById("inch").offsetWidth + '-' +
document.getElementById("inch").offsetWidth;
if (typeof(screen.width) == "number")
x += '-' + screen.width;
if (typeof(screen.height) == "number")
x += '-' + screen.height;
if (typeof(screen.availWidth) == "number")
x += '-' + screen.availWidth;
if (typeof(screen.availHeight) == "number")
x += '-' + screen.availHeight;
if (typeof(screen.pixelDepth) == "number")
x += '-' + screen.pixelDepth;
if (typeof(screen.colorDepth) == "number")
x += '-' + screen.colorDepth;
params['browser'] = x;
transfer(url, params);
}
*/
}
