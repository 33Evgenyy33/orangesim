<?php

/* newsletter/templates/components/save.hbs */
class __TwigTemplate_87e66773ed7028ac494b76b95564e2063788978d16cb02901ff0df5877cf5ada extends Twig_Template
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
        echo "<div class=\"mailpoet_save_wrapper\">
    <div class=\"mailpoet_button_group\">
        <input type=\"button\" name=\"save\" value=\"";
        // line 3
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Save");
        echo "\" class=\"button button-primary mailpoet_save_button\" /><button class=\"button button-primary mailpoet_save_show_options\" ><span class=\"dashicons mailpoet_save_show_options_icon\"></span></button>
    </div>
    <input type=\"button\" name=\"next\" value=\"";
        // line 5
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Next");
        echo "\" class=\"button button-primary mailpoet_save_next\" />
    <span class=\"mailpoet_save_error\"></span>
    <div class=\"mailpoet_editor_last_saved mailpoet_hidden\">";
        // line 7
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Autosaved");
        echo " <span class=\"mailpoet_autosaved_at\"></span></div>
    <br />
    <ul class=\"mailpoet_save_options mailpoet_hidden\">
        <li class=\"mailpoet_save_option\"><a href=\"javascript:;\" class=\"mailpoet_save_template\">";
        // line 10
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Save as template");
        echo "</a></li>
        <li class=\"mailpoet_save_option\"><a href=\"javascript:;\" class=\"mailpoet_save_export\">";
        // line 11
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Export as template");
        echo "</a></li>
    </ul>
    <div class=\"clearfix\"></div>
    <div class=\"mailpoet_save_as_template_container mailpoet_hidden\">
        <p><b class=\"mailpoet_save_as_template_title\">";
        // line 15
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Save as template");
        echo "</b></p>
        <p><input type=\"text\" name=\"template_name\" value=\"\" placeholder=\"";
        // line 16
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Insert template name");
        echo "\" class=\"mailpoet_input mailpoet_save_as_template_name\" /></p>
        <p><input type=\"text\" name=\"template_description\" value=\"\" placeholder=\"";
        // line 17
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Insert template description");
        echo "\" class=\"mailpoet_input mailpoet_save_as_template_description\" /></p>
        <p><input type=\"button\" name=\"save_as_template\" value=\"";
        // line 18
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Save as template");
        echo "\" class=\"button button-primary mailpoet_button_full mailpoet_save_as_template\" /></p>
    </div>
    <div class=\"mailpoet_export_template_container mailpoet_hidden\">
        <p><b class=\"mailpoet_export_template_title\">";
        // line 21
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Export template");
        echo "</b></p>
        <p><input type=\"text\" name=\"export_template_name\" value=\"\" placeholder=\"";
        // line 22
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Template name");
        echo "\" class=\"mailpoet_input mailpoet_export_template_name\" /></p>
        <p><input type=\"button\" name=\"export_template\" value=\"";
        // line 23
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Export template");
        echo "\" class=\"button button-primary mailpoet_button_full mailpoet_export_template\" /></p>
    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/components/save.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  76 => 23,  72 => 22,  68 => 21,  62 => 18,  58 => 17,  54 => 16,  50 => 15,  43 => 11,  39 => 10,  33 => 7,  28 => 5,  23 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/components/save.hbs", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\newsletter\\templates\\components\\save.hbs");
    }
}
