<?php

/* welcome.html */
class __TwigTemplate_8f3d9838521fa91eddb343d8617ca2a4d0332a7f244d0938fb20c27d6ac55df3 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.html", "welcome.html", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.html";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "
<div class=\"wrap mailpoet-about-wrap\">
  <h1 class=\"welcome\">";
        // line 6
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Greetings, humans.");
        echo "</h1>

  <p class=\"about-text\">";
        // line 8
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("The new MailPoet. Simply better. And with regular updates.");
        echo "</p>
  <div class=\"mailpoet-logo\"><img src=\"";
        // line 9
        echo $this->env->getExtension('MailPoet\Twig\Assets')->generateImageUrl("welcome_template/mailpoet-logo.png");
        echo "\" alt=\"";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("MailPoet Logo");
        echo "\" /></div>

  ";
        // line 11
        $context["random"] = twig_random($this->env, 2);
        // line 12
        echo "
  <div ";
        // line 13
        if ((($context["random"] ?? null) != 0)) {
            echo "style=\"display: none;\"";
        }
        echo ">
    <h2>";
        // line 14
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Coming this Fall to a WordPress plugin page near you");
        echo "</h2>
  <p class=\"about-text mailpoet_centered mailpoet-top-text\">";
        // line 15
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("The highly-anticipated sequel to Dead Poets Society, Introduction to MailPoet will keep you on the edge of your seat. \"Five out of five stars,\" says Rafael Ehlers, MailPoet Support Manager. \"A must-watch for aspiring email poets.\"");
        echo "</p>
  </div>
  <div ";
        // line 17
        if ((($context["random"] ?? null) != 1)) {
            echo "style=\"display: none;\"";
        }
        echo ">
    <h2>";
        // line 18
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("But first, watch this video");
        echo "</h2>
  <p class=\"about-text mailpoet_centered mailpoet-top-text\">";
        // line 19
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Wait a second. Don't click that big blue button just yet. Yeah, we're talking to you. Before you do anything, you should really watch this video. It won't change your life, but it will save you (and our support team) a ton of time. And what is life without time? So, maybe it is life-changing, after all...");
        echo "</p>
  </div>
  <div ";
        // line 21
        if ((($context["random"] ?? null) != 2)) {
            echo "style=\"display: none;\"";
        }
        echo ">
    <h2>";
        // line 22
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Explanatory videos are boring, we know");
        echo "</h2>
  <p class=\"about-text mailpoet_centered mailpoet-top-text\">";
        // line 23
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("But this video is really important! We promise. Over the course of three minutes, you'll learn how to send your first newsletter, manage your lists, make billions of dollars and live happily ever after. That's right – it's that good. So get watching.");
        echo "</p>
  </div>

  <div class=\"headline-feature feature-video\">
    <div class=\"videoWrapper\">
      <iframe width=\"1050\" height=\"591\" src=\"https://player.vimeo.com/video/229737424\" frameborder=\"0\" webkitallowfullscreen=\"\" mozallowfullscreen=\"\" allowfullscreen=\"\"></iframe>
    </div>
  </div>

  <hr>

  <div class=\"feature-section two-col\">
    <div class=\"col\">
      <h3>";
        // line 36
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Documentation Is One Click Away");
        echo "</h3>
      <p>";
        // line 37
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Simply click on the blue circle in the bottom right corner of any of the MailPoet pages to access our documentation.");
        echo " <em>";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Voilà!");
        echo "</em>
    </div>
    <div class=\"col\">
      <h3>";
        // line 40
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Sharing is Caring");
        echo "</h3>
      <p>";
        // line 41
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("By sharing your data <i>anonymously</i> with us, you can help us understand how people use MailPoet and what sort of features they like and don't like.");
        echo " <a href=\"http://beta.docs.mailpoet.com/article/130-sharing-your-data-with-us\" target=\"_blank\">";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Find out more");
        echo " &rarr;</a>
      <br><br>
      <label>
        <input type=\"checkbox\" id=\"mailpoet_analytics_enabled\" value=\"1\"
        ";
        // line 45
        if ($this->getAttribute($this->getAttribute(($context["settings"] ?? null), "analytics", array()), "enabled", array())) {
            echo "checked=\"checked\"";
        }
        // line 46
        echo "        />&nbsp;";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Yes, I want to help!");
        echo "
      </label>
      </p>
    </div>
  </div>

  <hr>

  <div class=\"feature-section one-col mailpoet_centered\">
    <a class=\"button button-primary go-to-plugin\" href=\"admin.php?page=mailpoet-newsletters\">";
        // line 55
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Awesome! Now, take me to MailPoet");
        echo " &rarr;</a>
  </div>

</div>

<script type=\"text/javascript\">
jQuery(function(\$) {
  // Find all videos
  var \$allVideos = \$(\"iframe[src^='//player.vimeo.com'], iframe[src^='//www.youtube.com']\"),
  // The element that is fluid width
  \$fluidEl = \$(\"body\");
  // Figure out and save aspect ratio for each video
  \$allVideos.each(function() {
    \$(this)
      .data('aspectRatio', this.height / this.width)
      // and remove the hard coded width/height
      .removeAttr('height')
      .removeAttr('width');
  });
  // When the window is resized
  \$(window).resize(function() {
  var newWidth = \$fluidEl.width();
  // Resize all videos according to their own aspect ratio
  \$allVideos.each(function() {
    var \$el = \$(this);
    \$el
      .width(newWidth)
      .height(newWidth * \$el.data('aspectRatio'));
    });
  // Kick off one resize to fix all videos on page load
  }).resize();

    \$(function() {
      \$(\"#mailpoet_analytics_enabled\").on(\"click\", function() {
        var is_enabled = \$(this).is(\":checked\") ? true : \"\";
        MailPoet.Ajax.post({
          api_version: window.mailpoet_api_version,
          endpoint: \"settings\",
          action: \"set\",
          data: {
            analytics: { enabled: (is_enabled)}
          }
        }).fail(function(response) {
          if (response.errors.length > 0) {
            MailPoet.Notice.error(
              response.errors.map(function(error) { return error.message; }),
              { scroll: true }
            );
          }
        });

        if (is_enabled) {
          MailPoet.forceTrackEvent(
            'User has installed MailPoet',
            {'MailPoet Free version': window.mailpoet_version}
          );
        }

      })
    });
});
</script>

";
    }

    public function getTemplateName()
    {
        return "welcome.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  154 => 55,  141 => 46,  137 => 45,  128 => 41,  124 => 40,  116 => 37,  112 => 36,  96 => 23,  92 => 22,  86 => 21,  81 => 19,  77 => 18,  71 => 17,  66 => 15,  62 => 14,  56 => 13,  53 => 12,  51 => 11,  44 => 9,  40 => 8,  35 => 6,  31 => 4,  28 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "welcome.html", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\welcome.html");
    }
}
