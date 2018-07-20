<?php

/* newsletter/templates/blocks/posts/settingsSelectionEmpty.hbs */
class __TwigTemplate_d2c9d068420394be3b1b0d14a8d3ea7885be7b0045752a70ad8716a3ced37fc2 extends Twig_Template
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
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("No posts available");
        echo "
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/posts/settingsSelectionEmpty.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/blocks/posts/settingsSelectionEmpty.hbs", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\newsletter\\templates\\blocks\\posts\\settingsSelectionEmpty.hbs");
    }
}
