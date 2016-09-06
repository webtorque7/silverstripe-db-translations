<div class="field locale-switcher">
    <ul>
        <% loop $Locales %>
            <li data-param="$Up.Param" data-locale="$Locale" <% if $Top.CurrentLocale == $Locale %>class="selected"<% end_if %>>
                $Label
            </li>
        <% end_loop %>
    </ul>
</div>