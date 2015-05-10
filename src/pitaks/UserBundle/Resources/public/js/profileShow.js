
function showList(){
    $.ajax({
        url:  Routing.generate('userReservationList'),
        type: "get",
        success: function(data){
            $('#userReservationsList').html(data);
        },
        error:function(){
            $('#userReservationsList').html("Where was some errors while loading your reservations");
        }
    });
}
showList();

function showUnconfirmedList(){
    $.ajax({
        url:  Routing.generate('userUnconfirmedReservationList'),
        type: "get",
        success: function(data){
            $('#userUnconfirmedReservationsList').html(data);
        },
        error:function(){
            $('#userUnconfirmedReservationsList').html("Where was some errors while loading your unconfirmed reservations");
        }
    });
}
showUnconfirmedList();
function showSentList(){
    $.ajax({
        url: Routing.generate('userSendedRegisteredReservation'),
        type: "get",
        success: function(data){
            $('#userSentReservations').html(data);
        },
        error:function(){
            $('#userSentReservations').html("Where was some errors while loading your unconfirmed reservations");
        }
    });
}
showSentList();

function showUsersTeams(){
    $.ajax({
        url:  Routing.generate('showUserTeam'),
        type: "get",
        success: function(data){
            $('#usersTeamList').html(data);
        },
        error:function(){
            $('#usersTeamList').html("Error while loading your teams");
        }
    });
}
showUsersTeams();

function showSuggestedUsersToTeams(){
    $.ajax({
        url: Routing.generate('showSuggestedTeams'),
        type: "get",
        success: function(data){
            $('#SuggestedTeamList').html(data);
        },
        error:function(){
            $('#SuggestedTeamList').html("Error while loading your teams");
        }
    });
}
showSuggestedUsersToTeams();

function showInvitedUserTeams(){
    $.ajax({
        url:  Routing.generate('showInvitedTeams'),
        type: "get",
        success: function(data){
            $('#userInvitesToCreateATeam').html(data);
        },
        error:function(){
            $('#userInvitesToCreateATeam').html("Error while loading your teams");
        }
    });
}
showInvitedUserTeams();
function showLastUserGames(){
    $.ajax({
        url:  Routing.generate('lastUserGames'),
        type: "get",
        success: function(data){
            $('#userLastGames').html(data);
        },
        error:function(){
            $('#userLastGames').html("Error while loading your teams");
        }
    });
}
showLastUserGames();

function showUserTableRating(){
    $.ajax({
        url:  Routing.generate('userTableRates'),
        type: "get",
        success: function(data){
            $('#usersTableRates').html(data);
        },
        error:function(){
            $('#usersTableRates').html("Error while loading your teams");
        }
    });
}
showUserTableRating();
$('#moreReservations').click( function()
{
    $('#NewsHome').removeClass('active');
    $('#ReservationHome').addClass('active');
});
$('#moreTeams').click( function()
{
    $('#NewsHome').removeClass('active');
    $('#TeamsHome').addClass('active');
});
