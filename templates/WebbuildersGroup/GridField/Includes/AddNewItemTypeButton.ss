<div class="addNewItemTypeButton">
    <% if $DropdownValues %>
        <div class="field dropdown" style="display: inline-block;">
            <select class="dropdown no-change-track">
                <% if $EmptyLabel %>
                    <option value="">$EmptyLabel</option>
                <% end_if %>
                
                <% loop $DropdownValues %>
                    <option value="$Class"<% if $Class==$Up.Default %> selected="selected"<% end_if %>>$Title</option>
                <% end_loop %>
            </select>
        </div>
    <% end_if %>
    <a href="$NewLink" class="action action-detail btn btn-primary font-icon-plus-circled new new-link new-item-type-add" data-icon="add" style="vertical-align: top;">$ButtonName</a>
</div>