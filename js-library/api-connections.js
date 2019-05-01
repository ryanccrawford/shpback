    
const walmartApiKey = 'yetbamnvuptfsnzehnsz99nr'
const googleApiKey = 'AIzaSyDiaHiIDgafsFhfwb1XQBtKETZ1zdlrP_o'
const shoppingListApiKey = 'q98ejf-fqwefj-8wefqw8w'

var userslists = [{
    userid: null,
    listid: null,
    items: [{itemid:null,name:null,categoryid:null,category:null}]

}];

//---------------------------ENDPOINT OBJECTS---------------------------------
var dataEnpoints = {
    createEndpoint: function (_endpoint,_action) {  
        return this.datahost + '/' + _endpoint + 'action=' + _action + '&' +'apiKey=' + this.apiKey
    },
    datahost: 'https://fe41a14.online-server.cloud',
    'apiKey': shoppingListApiKey,
    users: 'api.php?api=users&',
    lists: 'api.php?api=list&',
    listItems: 'api.php?api=listItems&',
    items: 'api.php?api=items&'

}
var walmartEnpoints = {
    createEndpoint: function (_endpoint) {
        var ep = ''
        if (Array.isArray(_endpoint)) {
            _endpoint.forEach(function (item) {
                ep += item
            })
        } else {
            ep += _endpoint
        }
        return this.walmartHost + '/' + ep + 'apiKey=' + this.apiKey
    },
    walmartHost: 'http://api.walmartlabs.com/v1',
    'apiKey': walmartApiKey,
    search: function (_query) {
        return ['search', ('?' + 'query=' + _query + '&')]
    },
    itemLookup: function (_lookUpParam) {
        return 'items' + _lookUpParam
    },
    valueOfDay: 'vod',
    taxonomy: 'taxonomy',
    locator: 'stores',
    trending: 'trends'
}
var walmart_lookUpObj = {
    createlookupString: function (_obj) {
        if (_obj.upc) {
            return this.upc(_obj.upc)
        } else {
            return this.itemIds(_obj.ids)
        }
    },
    itemIds: function (_itemIds) { 
        var param = ''
        if (Array.isArray(_itemIds)) {
            param += '?ids='
            param += _itemIds.join(',') + '&'
        } else {
            param += '/' + _itemIds + '?'
        }
        return param
     },
    upc: function (upc) {
        return '?upc=' + upc + '&'
    }
}
//---------------------------PHP API USER FUNCTIONS---------------------------
function data_AddUser(_email,_password,_zip) {
   var endPoint = dataEnpoints.users
    var url = dataEnpoints.createEndpoint(endPoint, 'insert')
    var data = {
        email: _email,
        password: _password,
        zip: _zip
    }
    $.ajax({
        type: "POST",
        url: url,
        data: data
    }).then(function (response) {
        addedUserEvent(response)
    });
}
function data_LogInUser(_email, _password) {
    var endPoint = dataEnpoints.users
    var url = walmartEnpoints.createEndpoint(endPoint, 'select', {
        email: _email,
        password: _password
    })
    $.ajax({
        type: "GET",
        url: url
    }).then(function (response) {
        isLoggedInEvent(response)
    });
}


//-----------------------------------EVENT HANDLERS-----------------------------
function addedUserEvent(_data) {
    $.event.trigger({
        type: "addedUser",
        message: _data
    });
}
function isLoggedInEvent(_data) {
     $.event.trigger({
         type: "isLoggedIn",
         message: _data
     });
}
function getItemEventHandel(_data) {
    $.event.trigger({
        type: "getWalmartItem",
        message: _data
});
}
function getsSearchItemEventHandel(_data) {
    $.event.trigger({
        type: "getWalmartItemSearch",
        message: _data
    });
}
//------------------------------------WALMART API FUNCTIONS---------------------------------


/** send this function an object like this:
{ids: ['23423','23443','23111386'...] } array of walmart item ids(Up to 20) or 
{ids: '23111386' }for a single id or
{ upc: 'upcnumber' } for a upc code **/
function walmart_GetItems(_item_ids) { 
    var item_ids = walmart_lookUpObj.createlookupString(_item_ids)
    var endPoint = walmartEnpoints.itemLookup(item_ids)
    var url = walmartEnpoints.createEndpoint(endPoint)
     $.ajax({
         type: "GET",
         url: url
     }).then(function (response) {
            getItemEventHandel(response)
     });
}
function walmart_SearchItems(_query) {
      var endPoint = walmartEnpoints.search(_query)
      var url = walmartEnpoints.createEndpoint(endPoint)
      $.ajax({
          type: "GET",
          url: url
      }).then(function (response) {
          getsSearchItemEventHandel(response)
      });
}
//SORT LIST----------------------------------------------------------------------------------------------------------
function sorta_sort() {
    
}
//TODO: NOT DONE
function walmart_GetItemPrice(_item_id) {
     $.ajax({
         type: "GET",
         url: "url",
         data: "data",
         dataType: "dataType"
     }.then(function (response) {

     }));
}

//---------------------------------------GOOGLE MAP APIS------------------------------------------
//TODO: NOT DONE
var mapsApiKey = ''
//TODO: NOT DONE
var mapsEndpoints = {
    
}
//GOOGLE MAPS API REQUEST OBJECT
var mapsApiRequest = function (_startLocation, _storeAddress, _method = 'DRIVING') {
    return {
        origin: _startLocation,
        destination: _storeAddress,
            provideRouteAlternatives: false,
            travelMode: _method,
        unitSystem: google.maps.UnitSystem.IMPERIAL
    }
   
}

//Test area
$(document).ready(function () {

    //EVENT HANDLERS 
    $(document).on('getWalmartItem', function (response) {
        console.log(response.message)
    })
    $(document).on('getWalmartItemSearch', function (response) {
        console.log(response.message)
    })
    $(document).on('addedUser', function (response) {
        console.log(response.message)
    })

    //FUNCTION TEST AREA
    walmart_SearchItems('eggs')

    walmart_GetItems({
        upc: '035000521019'
    })

    data_AddUser('test2@live.com','123456','32792')
    
})