<div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2>WP Mautic</h2>

    <?php

    use D4rk0s\WpMauticApi\API\MauticAuth;

    if(get_option(MauticAuth::MAUTIC_OAUTH_OPTION)) {
        echo "<span>Token de connexion disponible</span>";
    }

    $securityHash = MauticAuth::getSecurityHash();
    update_option(MauticAuth::SECURITY_HASH, $securityHash);
    $actionUrl = get_site_url().'/wp-json/'.MauticAuth::API_NAMESPACE.'/'.MauticAuth::ENDPOINT.'?security_key='.$securityHash;
    ?>

    <form method="POST" action="<?php echo $actionUrl; ?>">
        <label>
        Mautic base url
        <input type="text" name="<?php echo MauticAuth::MAUTIC_BASEURL; ?>" value="<?php echo get_option(MauticAuth::MAUTIC_BASEURL); ?>"/>
        </label>
      <label>
        Mautic public key
        <input type="text" name="<?php echo MauticAuth::MAUTIC_API_PUBLIC_KEY; ?>" value="<?php echo get_option(MauticAuth::MAUTIC_API_PUBLIC_KEY); ?>"/>
      </label>
      <label>
        Mautic private key
        <input type="text" name="<?php echo MauticAuth::MAUTIC_API_PRIVATE_KEY; ?>" value="<?php echo get_option(MauticAuth::MAUTIC_API_PRIVATE_KEY); ?>"/>
      </label>
      <input type="submit" value="Sauvegarder">
    </form>
</div>
