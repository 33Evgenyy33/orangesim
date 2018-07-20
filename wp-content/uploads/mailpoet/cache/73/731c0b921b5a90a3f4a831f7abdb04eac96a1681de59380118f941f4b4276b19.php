<?php

/* newsletter/templates/components/styles.hbs */
class __TwigTemplate_364b6199ca91c9138b01dfabb1a8336983da9d8d04a9b4df048d3e533d05618e extends Twig_Template
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
        echo "<style type=\"text/css\">
    .mailpoet_text_block .mailpoet_content,
    .mailpoet_text_block .mailpoet_content p {
        color: {{ text.fontColor }};
        font-size: {{ text.fontSize }};
        font-family: {{fontWithFallback text.fontFamily }};
    }
    .mailpoet_text_block .mailpoet_content h1 {
        color: {{ h1.fontColor }};
        font-size: {{ h1.fontSize }};
        font-family: {{fontWithFallback h1.fontFamily }};
    }
    .mailpoet_text_block .mailpoet_content h2 {
        color: {{ h2.fontColor }};
        font-size: {{ h2.fontSize }};
        font-family: {{fontWithFallback h2.fontFamily }};
    }
    .mailpoet_text_block .mailpoet_content h3 {
        color: {{ h3.fontColor }};
        font-size: {{ h3.fontSize }};
        font-family: {{fontWithFallback h3.fontFamily }};
    }
    .mailpoet_content a {
        color: {{ link.fontColor }};
        text-decoration: {{ link.textDecoration }};
    }
    .mailpoet_container_block, .mailpoet_container {
        background-color: {{ wrapper.backgroundColor }};
    }
    #mailpoet_editor_main_wrapper {
        background-color: {{ body.backgroundColor }};
    }
</style>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/components/styles.hbs";
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
        return new Twig_Source("", "newsletter/templates/components/styles.hbs", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\newsletter\\templates\\components\\styles.hbs");
    }
}
