<div class="addNewItemTypeButton">
    <% if $DropdownValues %>
        <div class="field dropdown" style="display: inline-block;">
            <select class="dropdown no-change-track">
                <% if $EmptyLabel %>
                    <option value="">$EmptyLabel</option>
                <% end_if %>
                
                <% loop $DropdownValues %>
                    <option value="$Class">$Title</option>
                <% end_loop %>
            </select>
        </div>
    <% end_if %>
    
    <a href="$NewLink" class="action action-detail ss-ui-action-constructive ss-ui-button ui-button ui-widget ui-state-default ui-corner-all new new-link" data-icon="add" style="vertical-align: top;">$ButtonName</a>
</div>