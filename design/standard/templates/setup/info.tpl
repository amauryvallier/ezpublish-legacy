<h1>{'System information'|i18n('design/standard/setup')}</h1>

<div class="objectheader">
    <h2>eZ publish</h2>
</div>
<div class="object">
    <table width="500">
    <tr>
        <td><p><b>{'Version'|i18n('design/standard/setup','eZ publish version')}</b></p></td><td><p>{$ezpublish_version}</p></td>
    </tr>
    <tr>
        <td><p><b>{'SVN revision'|i18n('design/standard/setup','eZ publish version')}</b></p></td><td><p>{$ezpublish_revision}</p></td>
    </tr>
    <tr>
        <td><p><b>{'Extensions'|i18n('design/standard/setup','eZ publish extensions')}</b></p></td>
        <td>
            <p>
                {section loop=$ezpublish_extensions}{$:item}{delimiter}, {/delimiter}{/section}
            </p>
        </td>
    </tr>
    </table>
</div>

<div class="objectheader">
    <h2>PHP</h2>
</div>
<div class="object">
    <table width="100%">
    <tr>
        <td><p><b>{'Version'|i18n('design/standard/setup','PHP version')}</b></p></td><td><p>{$php_version}</p></td>
    </tr>
    <tr>
        <td><p><b>{'Extensions'|i18n('design/standard/setup','PHP extensions')}</b></p></td>
        <td>
            <p>
                {section loop=$php_loaded_extensions}{$:item}{delimiter}, {/delimiter}{/section}
            </p>
        </td>
    </tr>
    </table>
    <p>
        {section show=$php_ini.safe_mode}
            {'Safe mode is on.'|i18n('design/standard/setup')}<br/>
        {section-else}
            {'Safe mode is off.'|i18n('design/standard/setup')}<br/>
        {/section}
        {section show=$php_ini.open_basedir}
            {'Basedir restriction is on and set to %1.'|i18n('design/standard/setup',,array($php_ini.open_basedir))}<br/>
        {section-else}
            {'Basedir restriction is off.'|i18n('design/standard/setup')}<br/>
        {/section}
        {section show=$php_ini.register_globals}
            {'Global variable registration is on.'|i18n('design/standard/setup')}<br/>
        {section-else}
            {'Global variable registration is off.'|i18n('design/standard/setup')}<br/>
        {/section}
        {section show=$php_ini.file_uploads}
            {'File uploading is enabled.'|i18n('design/standard/setup')}<br/>
        {section-else}
            {'File uploading is disabled.'|i18n('design/standard/setup')}<br/>
        {/section}
        {'Maximum size of post data (text and files) is %1.'|i18n('design/standard/setup',,array($php_ini.post_max_size))}<br/>
        {'Script memory limit is %1.'|i18n('design/standard/setup',,array($php_ini.memory_limit))}<br/>
        {'Maximum execution time is %1 seconds.'|i18n('design/standard/setup',,array($php_ini.max_execution_time))}<br/>
    </p>
</div>

<div class="objectheader">
    <h2>{'Database'|i18n('design/standard/setup')}</h2>
</div>
<div class="object">
    <table width="500">
    <tr>
        <td><p><b>{'Type'|i18n('design/standard/setup','Database type')}</b></p></td><td><p>{$database_info}</p></td>
    </tr>
    <tr>
        <td><p><b>{'Charset'|i18n('design/standard/setup','Database charset')}</b></p></td><td><p>{$database_charset}</p></td>
    </tr>
    </table>
</div>
