<!-- Action Buttons Script Edit/Details/Remove... -->
<script type="text/template" id="action_buttons">
 <?php if ($allow['read'] OR $allow['update'] OR $allow['delete'] ): ?>
        <div style="width:90px;" >
        <?php if ( $allow['read'] ): ?>
	         <!-- <li><a class="details" href="">Details</a></li> -->
           <a class="btn btn-default btn-xs details" data-toggle="tooltip" data-placement="top" title="View Details" href=""><i class="fa fa-info-circle fa-lg" aria-hidden="true"></i></a>
        <?php endif ?>
        <?php if ( $allow['update'] ): ?>
           <!-- <li><a class="edit" href="">Edit</a></li> -->
           <!-- <li class="divider"></li> -->
           <a class="btn btn-default btn-xs edit" data-toggle="tooltip" data-placement="top" title="Edit" href=""><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
        <?php endif ?>
           <!-- <li class="divider"></li> -->
        <?php if ( $allow['create'] ): ?>
           <!-- <li><a class="createCopy" href="">Copy</a></li> -->
           <a class="btn btn-default btn-xs createCopy" data-toggle="tooltip" data-placement="top" title="Copy" href=""><i class="fa fa-files-o fa-lg" aria-hidden="true"></i></a>
        <?php endif ?>

        <?php if ( $allow['delete'] ): ?>
        <!--   <li><a class="delete" href="">Delete</a></li> -->
        <?php endif ?>
       <!--  </ul> -->
      </div>
 <?php endif ?>
</script>

<!-- Status Cell -->
<script type="text/template" id="tmpStatusCell">
    <span>statusishe <%=status%></span>
    <% if(status==1){ %>
    <span class="label label-warning"><%= status %></span>
    <% } else if(status==2) { %>
    <span class="label label-info"><%= status %>/span>
    <% } else if(status==3) { %>
    <span class="label label-success"><%= status %></span>
    <% } else if(status==4) { %>
    <span class="label label-danger"><%= status %></span>
    <% } %>
    
</script>
 
<script type="text/template" id="tmpTsMainRow">
  <tr id="<%= model.id %>">
    <td><button id="removeTsMainRow" type="button" class="btn btn-xs btn-danger" ><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
      <td> 
        <select id="tsProjActivityType" class="" style="width:100px">
        <option value="00" disabled="" selected><i></i></option>  
        <?php if (isset($activity_types)): ?>
          <?php foreach ($activity_types as $activity): ?>
            <option value="<?php echo $activity['id']; ?>" data-code="<?php echo $activity['code']; ?>"><?php echo $activity['name']; ?></option>
          <?php endforeach ?>
        <?php endif ?>
        </select>
      </td>
      <td> 
        <select id="tsProject" class="" style="width:150px; display:none;" >
        <option value="00" selected="" disabled=""></option>  
        <?php if (isset($project_list)): ?>
          <?php foreach ($project_list as $project): ?>
            <option value="<?php echo $project['id']; ?>" 
                    data-name="<?php echo $project['name']; ?>" 
                    data-manager="<?php echo $project['manager']; ?>" 
                    data-managerinitials="<?php echo $project['manager']; ?>" 
                    data-needtoaccept="<?php echo ($userinfo['dep_head_id'] == $project['manager_id'] OR $project['manager_id']==$userinfo['id'] OR $userinfo['head_of_dep'] == 1) ? 0:1 ; ?>"
                  ><?php echo $project['code']; ?>
            </option>
          <?php endforeach ?>
        <?php endif ?>
        </select>
        
        <input type="text" id="tsCommentAdditonal" name="tsCommentAdditonal" class="input" placeholder="Your notes here..." value="<%= model.tsComment %>" style="width:150px;display:none;">

      </td>
      <td>
        <!-- <a href="#" style="display:none;" id="tsOpRefresh" class="btn btn-xs btn-default"><i class="fa fa-refresh" aria-hidden="true"></i></a> -->
        <select id="tsOperation" class="" style="width:150px;display:none;">
        <option value="00" selected="" disabled=""></option>  
        </select>
      </td>
      <td>
        <span id="tsProjectManager"><%= typeof(model.tsProjectManager)!=='undefined' ? model.tsProjectManager:'' %></span>
      </td>
      <td>
        <input style="text-align:center;" class="tsWDMain input" size="1" type="text" id="tsWD1" name="tsWD1" value="<%= typeof(model.tsWD1)!=='undefined' ? model.tsWD1:0 %>">
      </td>
      <td>
        <input style="text-align:center;" class="tsWDMain input" size="1" type="text" id="tsWD2" name="tsWD2" value="<%= typeof(model.tsWD2)!=='undefined' ? model.tsWD2:0 %>">
      </td>
      <td>
        <input style="text-align:center;" class="tsWDMain input" size="1" type="text" id="tsWD3" name="tsWD3" value="<%= typeof(model.tsWD3)!=='undefined' ? model.tsWD3:0 %>">
      </td>
      <td>
        <input style="text-align:center;" class="tsWDMain input" size="1" type="text" id="tsWD4" name="tsWD4" value="<%= typeof(model.tsWD4)!=='undefined' ? model.tsWD4:0 %>">
      </td>
      <td>
        <input style="text-align:center;" class="tsWDMain input" size="1" type="text" id="tsWD5" name="tsWD5" value="<%= typeof(model.tsWD5)!=='undefined' ? model.tsWD5:0 %>">
      </td>
      <td style="text-align:center; background-color:#d9edf7;">
        <input style="text-align:center;" class="tsWDMain input" size="1" type="text" id="tsWD6" name="tsWD6" value="<%= typeof(model.tsWD6)!=='undefined' ? model.tsWD6:0 %>">
      </td>
      <td style="text-align:center; background-color:#d9edf7;">
        <input style="text-align:center;" class="tsWDMain input" size="1" type="text" id="tsWD7" name="tsWD7" value="<%= typeof(model.tsWD7)!=='undefined' ? model.tsWD7:0 %>">
      </td>
      <td>
        <span id="tsProjectTotal"></span>
      </td>
      <td align="center">
        <input type="text"  id="tsComment" name="tsComment" size="6" class="input" value="<%= typeof(model.tsComment)!=='undefined' ? model.tsComment:'' %>">
      </td>
  </tr>
</script>

<script type="text/template" id="tmpTsAbsenceRow">
  <tr id="<%= model.id %>">
    <td><button id="removeTsAbsenceRow" type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
      <td colspan="2"> 
        <select id="tsProjAbsenceType" class="myTsSelect2">
        <option value="00" selected="" disabled=""></option>  
        <?php if (isset($absence_types)): ?>
          <?php foreach ($absence_types as $absence): ?>
            <option value="<?php echo $absence['id']; ?>"><?php echo $absence['name']; ?></option>
          <?php endforeach ?>
        <?php endif ?>
        </select>
      </td>
      <td colspan="2"></td>
      
      <td>
        <input style="text-align:center;" class="tsWDAbsence input" size="1" type="text" id="tsWD1" name="tsWD1" value="<%= typeof(model.tsWD1)!=='undefined' ? model.tsWD1:0 %>">
      </td>
      <td>
        <input style="text-align:center;" class="tsWDAbsence input" size="1" type="text" id="tsWD2" name="tsWD2" value="<%= typeof(model.tsWD2)!=='undefined' ? model.tsWD2:0 %>">
      </td>
      <td>
        <input style="text-align:center;" class="tsWDAbsence input" size="1" type="text" id="tsWD3" name="tsWD3" value="<%= typeof(model.tsWD3)!=='undefined' ? model.tsWD3:0 %>">
      </td>
      <td>
        <input style="text-align:center;" class="tsWDAbsence input" size="1" type="text" id="tsWD4" name="tsWD4" value="<%= typeof(model.tsWD4)!=='undefined' ? model.tsWD4:0 %>">
      </td>
      <td>
        <input style="text-align:center;" class="tsWDAbsence input" size="1" type="text" id="tsWD5" name="tsWD5" value="<%= typeof(model.tsWD5)!=='undefined' ? model.tsWD5:0 %>">
      </td>
      <td style="text-align:center; background-color:#d9edf7;">
        <input style="text-align:center;" class="tsWDAbsence input" size="1" type="text" id="tsWD6" name="tsWD6" value="<%= typeof(model.tsWD6)!=='undefined' ? model.tsWD6:0 %>">
      </td>
      <td style="text-align:center; background-color:#d9edf7;">
        <input style="text-align:center;" class="tsWDAbsence input" size="1" type="text" id="tsWD7" name="tsWD7" value="<%= typeof(model.tsWD7)!=='undefined' ? model.tsWD7:0 %>">
      </td>
      <td>
        <span id="tsProjectTotal"></span>
      </td>
      <td align="center">
        <input type="text" id="tsComment" name="tsComment" size="6" class="input" value="<%= typeof(model.tsComment)!=='undefined' ? model.tsComment:'' %>">
      </td>
  </tr>
</script>

<script type="text/template" id="tmpTsUsers"> 
  <select class="myTsSelect2Searchable col-xs-3" id="tsFilterUsers">
    <option <?php echo 'value="'. $userinfo['id'].'" selected';?> > <?php echo $userinfo['name']." ".$userinfo['middle']." ".$userinfo['sname'];?> </option>
    <?php if ( $userinfo['ceo'] == 1 || $userinfo['head_of_dep'] == 1 || $userinfo['is_admin'] == 1 ): ?>
      <?php if (isset($user_list)): ?>
        <?php foreach ($user_list as $user): ?>
              <?php 
                $selected = '';
                if ( $user['id']==$userinfo['id'] )
                {
                      $selected='selected=""';
                }
              ?>
              <option <?php echo 'value="'. $user['id'].'" '. $selected ;?> > <?php echo $user['name']." ".$user['middle']." ".$user['sname'];?> </option>
        <?php endforeach ?>
      <?php endif ?>
  <?php else: ?>
  <?php endif ?>

  </select>
</script>



<script type="text/template" id="tmpTsWeeks"> 
  <select class="myTsSelect2Searchable col-xs-1" id="tsFilterWeeks" >
    <option value="0">All</option>";
    <?php 
      for ($i=1; $i <= 53; $i++) 
      { 
        echo "<option value=".$i.">".$i."</option>";
      }
     ?>
  </select>
</script>


<script type="text/template" id="tmpTsYears"> 
  <select class="myTsSelect2 col-xs-2" id="tsFilterYears" >
    <?php 
      $start_year = 2015;
      $end_year = Date('Y');
      
      while ( $start_year <= $end_year ) 
      {
        $selected = '';

        if ($start_year == $end_year) 
        {
          $selected = 'selected=""';
        }
        echo "<option value=" . $start_year . " " . $selected ." >" . $start_year . "</option>";
        $start_year++;
      }
     ?>
  </select>
</script>

