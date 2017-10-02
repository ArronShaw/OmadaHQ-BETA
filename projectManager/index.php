<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../js/angular.min.js"></script>
    <script src="projectManager.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        .project {
            background: #ecf0f1;
            padding: 15px;
            box-shadow: 1px 1px 1px 1px rgba(0,0,0,0.3);
            margin-bottom: 15px;
        }
        .project:hover {
            background: #d4d8d8;
        }
        .projectClicked {
            background: #d4d8d8;
            border-right: 8px solid #F6C800;
        }
        .goal {
            padding: 10px;
            border-bottom: 1px solid black;
            box-shadow: 1px 1px 0px 1px rgba(0,0,0,0.3);
            background: whitesmoke;
            cursor:pointer;
            height: 75px;
        }
        .goal > p {
            margin-bottom: -5px
        }
        .fa-trash {
            color: #e74c3c;
        }
        .fa-long-arrow-left{
            color: #ffc107!important;
            float: left;
            padding-top: 25px;
            font-size: 25px;
            margin-left: -5px;
        }
        .fa-long-arrow-right {
            color: green;
            float: right;
            margin-top: 25px;
            font-size: 25px;
            margin-right: -15px;
        }
        .goal-delete {
            float: right;
            font-size: 15px;
        }
        .edit {
            margin-left: -20px;
        }
        .color-tag {
            width: 15px;
            height: 15px;
            margin: 10px 5px 10px 0px;
            float: left;
            list-style-type: none;
        }
        .tagClicked {
            width: 20px;
            height: 20px;
        }
    </style>
</head>

<body>
    <div ng-app="myapp" ng-controller="usercontroller" ng-init="displayProject()">
        <h1>Project Manager - Taj version</h1>
        <div class="col-sm-2">
            <form name="projectEnter" style="margin-bottom: -35px;">
                <label>Project</label><br>
                <input style="width: 68%;" type="text" name="project" ng-model="project" autocomplete="off" autofocus required>
                <button type="submit" name="btnProject" ng-click="projectInsert();" value="{{btnProject}}" ng-disabled="projectEnter.$invalid" >{{btnProject}}</button>
                <li ng-repeat="x in tagColors" class="color-tag" style="background: {{x.color}};" ng-class="{ 'tagClicked': $index == selectedColor }" ng-click="tagClicked($index); projectInsert(x.color);">
                </li>
                <small style="color: red;" ng-show="projectError">Project cannot be more than 25 characters</small>
            </form><br><br>
            <h3 style="padding-top:23px"><i class="fa fa-folder-open fw"></i> Projects</h3>
            <div class="project" ng-repeat="x in projects | filter : {'0':''}" ng-click="filterGoals(x.project_id); showGoal(); projectClicked($index); hideProgress()" ng-class="{ 'projectClicked': $index == selectedProject }" style="cursor:pointer;">
                <p>{{x.project}}</p>
                <i ng-click="deleteProject(x.project_id)" class="fa fa-trash fw"></i>
            </div>
        </div>
        
        <div>
            <form name="goalEnter" ng-show="goalForm" style="margin-bottom: -5px; margin-top: 25px">
                <label style="margin-left: 15px;">Goal</label>
                <input style="width: 55%;" type="text" name="goal" ng-model="goal" autocomplete="off" autofocus required>
                <button type="submit" name="btnInsert" ng-click="goalInsert();" value="{{btnName}}" ng-disabled="goalEnter.$invalid">{{btnName}}
                </button><br>
                <small style="color: red; margin-left: 50px;" ng-show="goalError">Goal cannot be more than 28 characters</small>
            </form>
            <div style="padding-top:23px; margin-top: 25px" ng-hide="goalForm"></div>
            <div class="col-sm-2">
                <h3><i class="fa fa-times-circle fw" style="color: #e74c3c"></i> Not Started</h3>
                <div class="goal" ng-repeat="x in goals | filter : 'not_started'" ng-click="filterRecords(x.goal_id); showProgress()">
                    <p>{{x.goal}}</p>
                    <i ng-click="deleteData(x.goal_id)" class="goal-delete fa fa-trash fw"></i>
                    <small ng-click="updateData(x.goal_id, x.goal)">edit</small>
                    <i ng-click="advanceGoalStatus(x.goal_id, x.status)" class="fa fa-long-arrow-right fw"></i>
                </div>
            </div>

            <div class="col-sm-2">
                <h3><i class="fa fa-cogs fw" style="color: #e67e22;"></i> In Progress</h3>
                <div class="goal" ng-repeat="x in goals | filter : 'inProgress'" ng-click="filterRecords(x.goal_id); showProgress()">
                    <p>{{x.goal}}</p>
                    <i ng-click="deleteData(x.goal_id)" class="goal-delete fa fa-trash fw"></i>
                    <small class="edit" ng-click="updateData(x.goal_id, x.goal)">edit</small>
                    <i ng-click="reverseGoalStatus(x.goal_id, x.status)" class="fa fa-long-arrow-left fw"></i>
                    <i ng-click="advanceGoalStatus(x.goal_id, x.status)" class="fa fa-long-arrow-right fw"></i>
                </div>
            </div>

            <div class="col-sm-2">
                <h3><i class="material-icons fw" style="color: #f1c40f;">find_in_page</i> In Review</h3>
                <div class="goal" ng-repeat="x in goals | filter : 'inReview'" ng-click="filterRecords(x.goal_id); showProgress()">
                    <p>{{x.goal}}</p>
                    <i ng-click="deleteData(x.goal_id)" class="goal-delete fa fa-trash fw"></i>
                    <small class="edit" ng-click="updateData(x.goal_id, x.goal)"> edit</small>
                    <i ng-click="reverseGoalStatus(x.goal_id, x.status)" class="fa fa-long-arrow-left fw"></i>
                    <i ng-click="advanceGoalStatus(x.goal_id, x.status)" class="fa fa-long-arrow-right fw"></i>
                </div>
            </div>

            <div class="col-sm-2">
                <h3><i class="fa fa-check fw" style="color: #60a917;"></i> Completed</h3>
                <div class="goal" ng-repeat="x in goals | filter : 'completed'" ng-click="filterRecords(x.goal_id); showProgress()">
                    <p>{{x.goal}}</p>
                    <i ng-click="deleteData(x.goal_id)" class="goal-delete fa fa-trash fw"></i>
                    <small class="edit" ng-click="updateData(x.goal_id, x.goal)"> edit</small>
                    <i ng-click="reverseGoalStatus(x.goal_id, x.status)" class="fa fa-long-arrow-left fw"></i>
                </div>
            </div>
        </div>
        
        <div ng-show="progress_record" class="col-sm-2" style="height: 400px">
            <h3><i class="fa fa-file-text fw"></i> Progress Record</h3>
            <form name="recordForm" id="recordForm">
                <input type="text" id="recordField" ng-model="recordInput" name="progress" placeholder="Add record" autocomplete="off" autofocus required>
                <input type="submit" name="recordInsert" ng-click="submitRecord(recordInput); recordInput = null" ng-disabled="recordForm.$invalid">
            </form> 
            <div style="overflow-y: auto; height: 400px;">
                <div id="comments" ng-repeat="x in records | filter : {'initial_record':'N'} | orderBy : '-record_id'">
                    <p style="margin-bottom: 0px">{{x.record}}</p>
                    <span style="font-size: 10px;">{{x.timestamp | date : "EEE d MMM h:mm a"}}</span>
                    <span style="font-size: 10px; float: right;"> By {{x.user_id}}</span>
                </div>
                <div id="first-comment" width="100px" ng-repeat="x in records | filter : {'initial_record':'Y'} | orderBy : '-record_id'">
                    <h4 style="margin-bottom: 0px">{{x.record}} {{x.user}}</h4>
                    <span style="font-size: 10px;">{{x.timestamp | date : "EEE d MMM h:mm a"}}</span>
    <!--                <span style="font-size: 10px; float: right;"> By {{x.user}}</span>-->
                </div>  
            </div>
        </div>
    </div>
</body>

</html>
