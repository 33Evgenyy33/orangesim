<?php

/* newsletter/templates/blocks/social/block.hbs */
class __TwigTemplate_efa70fe8c5e2d5abcd663853660ed8961e76301ab5b8e0d920e3747d2b836962 extends Twig_Template
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
        echo "<div class=\"mailpoet_tools\"></div>
<div class=\"mailpoet_content mailpoet_social\"></div>
<div class=\"mailpoet_block_highlight\"></div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/social/block.hbs";
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
        return new Twig_Source("", "newsletter/templates/blocks/social/block.hbs", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\newsletter\\templates\\blocks\\social\\block.hbs");
    }
}
