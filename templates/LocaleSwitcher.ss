<div class="field locale-switcher">
    <ul>
        <% loop $Locales %>
            <li data-locale="$Locale" <% if $Top.CurrentLocale == $Locale %>class="selected"<% end_if %>>
                $Label
            </li>
        <% end_loop %>
    </ul>
</div>