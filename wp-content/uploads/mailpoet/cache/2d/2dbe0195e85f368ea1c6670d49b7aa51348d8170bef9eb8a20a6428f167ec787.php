<?php

/* newsletter/templates/components/sidebar/preview.hbs */
class __TwigTemplate_3ba5e73bb8459585fe4531fc07666f99a61254ea25bb6f30eb3fbe1bc8545dbd extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"handlediv\" title=\"Click to toggle\"><br></div>
<h3>";
        // line 2
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Preview");
        echo "</h3>
<div class=\"mailpoet_region_content\">
    <iframe name=\"mailpoet_save_preview_email_for_autocomplete\" style=\"display:none\" src=\"about:blank\"></iframe>
    <form target=\"mailpoet_save_preview_email_for_autocomplete\">
      <div class=\"mailpoet_form_field\">
          <label>
              ";
        // line 8
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Send preview to");
        echo "<br />
              <input id=\"mailpoet_preview_to_email\" class=\"mailpoet_input mailpoet_input_full\" type=\"text\" name=\"to_email\" value=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->getAttribute(($context["current_wp_user"] ?? null), "email", array()), "html", null, true);
        echo "\" autocomplete=\"email\" />
          </label>
      </div>

      <div class=\"mailpoet_form_field\">
        <input type=\"submit\" id=\"mailpoet_send_preview\" class=\"button button-primary mailpoet_button_full\" value=\"";
        // line 14
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Send preview");
        echo "\" />
        <span id=\"tooltip-send-preview\" class=\"tooltip-help-send-preview\"></span>
      </div>
    </form>

    <hr class=\"mailpoet_separator\" />

    <input type=\"button\" name=\"preview\" class=\"button button-primary mailpoet_button_full mailpoet_show_preview\" value=\"";
        // line 21
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("View in browser");
        echo "\" />
</div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/components/sidebar/preview.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  53 => 21,  43 => 14,  35 => 9,  31 => 8,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/components/sidebar/preview.hbs", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\newsletter\\templates\\components\\sidebar\\preview.hbs");
    }
}
