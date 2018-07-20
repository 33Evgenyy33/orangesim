<?php

/* layout.html */
class __TwigTemplate_80a7e2b28f6471606d47ac244a0f2c782e83a8818707a0ba422a7d5cfbc95590 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'templates' => array($this, 'block_templates'),
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
            'after_css' => array($this, 'block_after_css'),
            'translations' => array($this, 'block_translations'),
            'after_translations' => array($this, 'block_after_translations'),
            'after_javascript' => array($this, 'block_after_javascript'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        if (($context["sub_menu"] ?? null)) {
            // line 2
            echo "<script type=\"text/javascript\">
jQuery('.toplevel_page_mailpoet-newsletters.menu-top-last')
  .addClass('wp-has-current-submenu')
  .find('a[href\$=\"";
            // line 5
            echo twig_escape_filter($this->env, ($context["sub_menu"] ?? null), "html", null, true);
            echo "\"]')
  .addClass('current')
  .parent()
  .addClass('current');
</script>
";
        }
        // line 11
        echo "
<!-- system notices -->
<div id=\"mailpoet_notice_system\" class=\"mailpoet_notice\" style=\"display:none;\"></div>

<!-- handlebars templates -->
";
        // line 16
        $this->displayBlock('templates', $context, $blocks);
        // line 17
        echo "
<!-- main container -->
<div class=\"wrap\">
  <!-- notices -->
  <div id=\"mailpoet_notice_error\" class=\"mailpoet_notice\" style=\"display:none;\"></div>
  <div id=\"mailpoet_notice_success\" class=\"mailpoet_notice\" style=\"display:none;\"></div>

  <!-- title block -->
  ";
        // line 25
        $this->displayBlock('title', $context, $blocks);
        // line 26
        echo "  <!-- content block -->
  ";
        // line 27
        $this->displayBlock('content', $context, $blocks);
        // line 28
        echo "</div>

<!-- stylesheets -->
";
        // line 31
        echo $this->env->getExtension('MailPoet\Twig\Assets')->generateStylesheet("admin.css");
        // line 33
        echo "

";
        // line 35
        echo do_action("mailpoet_styles_admin_after");
        echo "

<!-- rtl specific stylesheet -->
";
        // line 38
        if ($this->env->getExtension('MailPoet\Twig\Functions')->isRtl()) {
            // line 39
            echo "  ";
            echo $this->env->getExtension('MailPoet\Twig\Assets')->generateStylesheet("rtl.css");
            echo "
";
        }
        // line 41
        echo "
";
        // line 42
        $this->displayBlock('after_css', $context, $blocks);
        // line 43
        echo "
<script type=\"text/javascript\">
  var mailpoet_date_format = \"";
        // line 45
        echo twig_escape_filter($this->env, twig_escape_filter($this->env, $this->env->getExtension('MailPoet\Twig\Functions')->getWPDateTimeFormat(), "js"), "html", null, true);
        echo "\";
  var mailpoet_time_format = \"";
        // line 46
        echo twig_escape_filter($this->env, twig_escape_filter($this->env, $this->env->getExtension('MailPoet\Twig\Functions')->getWPTimeFormat(), "js"), "html", null, true);
        echo "\";
  var mailpoet_version = \"";
        // line 47
        echo $this->env->getExtension('MailPoet\Twig\Functions')->getMailPoetVersion();
        echo "\";
  var mailpoet_premium_version = ";
        // line 48
        echo json_encode($this->env->getExtension('MailPoet\Twig\Functions')->getMailPoetPremiumVersion());
        echo ";
  var mailpoet_analytics_enabled = ";
        // line 49
        echo twig_escape_filter($this->env, twig_jsonencode_filter(call_user_func_array($this->env->getFunction('is_analytics_enabled')->getCallable(), array())), "html", null, true);
        echo ";
  var mailpoet_analytics_data = ";
        // line 50
        echo json_encode(call_user_func_array($this->env->getFunction('get_analytics_data')->getCallable(), array()));
        echo ";
  // RFC 5322 standard; http://emailregex.com/ combined with https://google.github.io/closure-library/api/goog.format.EmailAddress.html#isValid
  var mailpoet_email_regex = /(?=^[+a-zA-Z0-9_.!#\$%&'*\\/=?^`{|}~-]+@([a-zA-Z0-9-]+\\.)+[a-zA-Z0-9]{2,63}\$)(?=^(([^<>()\\[\\]\\\\.,;:\\s@\"]+(\\.[^<>()\\[\\]\\\\.,;:\\s@\"]+)*)|(\".+\"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,})))/;
</script>

<!-- javascripts -->
";
        // line 56
        echo $this->env->getExtension('MailPoet\Twig\Assets')->generateJavascript("vendor.js", "mailpoet.js");
        // line 59
        echo "

";
        // line 61
        echo $this->env->getExtension('MailPoet\Twig\I18n')->localize(array("ajaxFailedErrorMessage" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("An error has happened while performing a request, the server has responded with response code %d")));
        // line 63
        echo "
";
        // line 64
        $this->displayBlock('translations', $context, $blocks);
        // line 65
        echo "
";
        // line 66
        $this->displayBlock('after_translations', $context, $blocks);
        // line 67
        echo "
";
        // line 68
        echo $this->env->getExtension('MailPoet\Twig\Assets')->generateJavascript("admin_vendor.js");
        // line 70
        echo "

";
        // line 72
        echo do_action("mailpoet_scripts_admin_before");
        echo "

";
        // line 74
        echo $this->env->getExtension('MailPoet\Twig\Assets')->generateJavascript("admin.js");
        // line 76
        echo "

";
        // line 78
        echo $this->env->getExtension('MailPoet\Twig\Assets')->generateJavascript("lib/analytics.js");
        echo "

";
        // line 80
        $context["has_valid_premium_key"] = $this->env->getExtension('MailPoet\Twig\Functions')->hasValidPremiumKey();
        // line 81
        $context["helpscout_form_id"] = ((($context["has_valid_premium_key"] ?? null)) ? ("6974b88d-8d85-11e7-b5b5-0ec85169275a") : ("dd918048-8d73-11e7-b5b5-0ec85169275a"));
        // line 82
        echo "<script>!function(e,o,n){window.HSCW=o,window.HS=n,n.beacon=n.beacon||{};var t=n.beacon;t.userConfig={},t.readyQueue=[],t.config=function(e){this.userConfig=e},t.ready=function(e){this.readyQueue.push(e)},o.config={docs:{enabled:!0,baseUrl:\"https://mailpoet3.helpscoutdocs.com/\"},contact:{enabled:!0,formId:\"";
        echo twig_escape_filter($this->env, ($context["helpscout_form_id"] ?? null), "html", null, true);
        echo "\"}};var r=e.getElementsByTagName(\"script\")[0],c=e.createElement(\"script\");c.type=\"text/javascript\",c.async=!0,c.src=\"https://djtflbt20bdde.cloudfront.net/\",r.parentNode.insertBefore(c,r)}(document,window.HSCW||{},window.HS||{});</script>

<script type=\"text/javascript\">
  if(window['HS'] !== undefined) {
    // HelpScout Beacon: Configuration
    HS.beacon.config({
      icon: 'message',
      zIndex: 50000,
      instructions: \"";
        // line 90
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Want to give feedback to the MailPoet team? Contact us here. Please provide as much information as possible!");
        echo "\",
      showContactFields: true
    });

    // HelpScout Beacon: Custom information
    HS.beacon.ready(function() {
      HS.beacon.identify(
        ";
        // line 97
        echo json_encode(\MailPoet\Helpscout\Beacon::getData());
        echo "
      );
    });
  }
</script>

";
        // line 103
        if (((($context["has_valid_premium_key"] ?? null) == false) && $this->env->getExtension('MailPoet\Twig\Functions')->installedInLastTwoWeeks())) {
            // line 104
            echo "  <script type=\"text/javascript\" async>;(function(o,l,a,r,k,y){if(o.olark)return;r=\"script\";y=l.createElement(r);r=l.getElementsByTagName(r)[0];y.async=1;y.src=\"//\"+a;r.parentNode.insertBefore(y,r);y=o.olark=function(){k.s.push(arguments);k.t.push(+new Date)};y.extend=function(i,j){y(\"extend\",i,j)};y.identify=function(i){y(\"identify\",k.i=i)};y.configure=function(i,j){y(\"configure\",i,j);k.c[i]=j};k=y._={s:[],t:[+new Date],c:{},l:a};})(window,document,\"static.olark.com/jsclient/loader.js\");olark.identify('2129-842-10-9194');</script>

  <script type=\"text/javascript\">
    olark('api.box.onShow', function() {
        jQuery('#hs-beacon').hide();
    });
  </script>
";
        }
        // line 112
        echo "
";
        // line 113
        $this->displayBlock('after_javascript', $context, $blocks);
    }

    // line 16
    public function block_templates($context, array $blocks = array())
    {
    }

    // line 25
    public function block_title($context, array $blocks = array())
    {
    }

    // line 27
    public function block_content($context, array $blocks = array())
    {
    }

    // line 42
    public function block_after_css($context, array $blocks = array())
    {
    }

    // line 64
    public function block_translations($context, array $blocks = array())
    {
    }

    // line 66
    public function block_after_translations($context, array $blocks = array())
    {
    }

    // line 113
    public function block_after_javascript($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "layout.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  258 => 113,  253 => 66,  248 => 64,  243 => 42,  238 => 27,  233 => 25,  228 => 16,  224 => 113,  221 => 112,  211 => 104,  209 => 103,  200 => 97,  190 => 90,  178 => 82,  176 => 81,  174 => 80,  169 => 78,  165 => 76,  163 => 74,  158 => 72,  154 => 70,  152 => 68,  149 => 67,  147 => 66,  144 => 65,  142 => 64,  139 => 63,  137 => 61,  133 => 59,  131 => 56,  122 => 50,  118 => 49,  114 => 48,  110 => 47,  106 => 46,  102 => 45,  98 => 43,  96 => 42,  93 => 41,  87 => 39,  85 => 38,  79 => 35,  75 => 33,  73 => 31,  68 => 28,  66 => 27,  63 => 26,  61 => 25,  51 => 17,  49 => 16,  42 => 11,  33 => 5,  28 => 2,  26 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "layout.html", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\layout.html");
    }
}
