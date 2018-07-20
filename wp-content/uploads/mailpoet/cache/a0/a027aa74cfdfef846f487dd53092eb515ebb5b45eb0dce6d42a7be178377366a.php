<?php

/* newsletter/templates/blocks/container/settings.hbs */
class __TwigTemplate_d25832f8d1a3b34ca41947f41adad0f882808e0d1322bf6f93975cf4a7ca47c5 extends Twig_Template
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
        echo "<h3>";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Columns");
        echo "</h3>
<div class=\"mailpoet_form_field\">
    <label>
        <div class=\"mailpoet_form_field_input_option\">
            <input type=\"text\" name=\"background-color\" class=\"mailpoet_field_container_background_color mailpoet_color\" value=\"{{ model.styles.block.backgroundColor }}\" />
        </div>
        <div class=\"mailpoet_form_field_title mailpoet_form_field_title_inline\">";
        // line 7
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Background");
        echo "</div>
    </label>
</div>

<div class=\"mailpoet_container_columns_settings\"></div>

<div class=\"mailpoet_form_field\">
    <input type=\"button\" class=\"button button-primary mailpoet_done_editing\" value=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('MailPoet\Twig\I18n')->translate("Done"), "html_attr");
        echo "\" />
</div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/container/settings.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  39 => 14,  29 => 7,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/blocks/container/settings.hbs", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\newsletter\\templates\\blocks\\container\\settings.hbs");
    }
}
