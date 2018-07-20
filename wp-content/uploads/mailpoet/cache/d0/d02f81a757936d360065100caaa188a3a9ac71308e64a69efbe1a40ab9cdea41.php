<?php

/* newsletter/templates/blocks/text/widget.hbs */
class __TwigTemplate_128ccee2ed3d7aaa06643092998008e60bc60b0f487f35d4fb66e843f6a3508a extends Twig_Template
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
        echo "<div class=\"mailpoet_widget_icon\">
";
        // line 2
        echo twig_source($this->env, "newsletter/templates/svg/block-icons/text.svg");
        echo "
</div>
<div class=\"mailpoet_widget_title\">";
        // line 4
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Text");
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/text/widget.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  27 => 4,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/blocks/text/widget.hbs", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\newsletter\\templates\\blocks\\text\\widget.hbs");
    }
}
