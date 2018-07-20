<?php

/* newsletter/templates/blocks/button/block.hbs */
class __TwigTemplate_e2338e1fcfec3181b0f003a5587b917cf306b3350dded1deac5664f1609727c5 extends Twig_Template
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
<div class=\"mailpoet_content\">
    <a href=\"{{ model.url }}\" class=\"mailpoet_editor_button\" style=\"{{#ifCond model.styles.block.textAlign '==' 'left'}}margin: 0 auto 0 0; {{/ifCond}}{{#ifCond model.styles.block.textAlign '==' 'center'}}margin: auto; {{/ifCond}}{{#ifCond model.styles.block.textAlign '==' 'right'}}margin: 0 0 0 auto; {{/ifCond}}line-height: {{ model.styles.block.lineHeight }}; width: {{ model.styles.block.width }}; background-color: {{ model.styles.block.backgroundColor }}; color: {{ model.styles.block.fontColor }}; font-family: {{fontWithFallback model.styles.block.fontFamily }}; font-size: {{ model.styles.block.fontSize }}; font-weight: {{ model.styles.block.fontWeight }}; border: {{ model.styles.block.borderWidth }} {{ model.styles.block.borderStyle }} {{ model.styles.block.borderColor }}; border-radius: {{ model.styles.block.borderRadius }};\" onClick=\"return false;\">{{ model.text }}</a>
</div>
<div class=\"mailpoet_block_highlight\"></div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/button/block.hbs";
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
        return new Twig_Source("", "newsletter/templates/blocks/button/block.hbs", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\newsletter\\templates\\blocks\\button\\block.hbs");
    }
}
