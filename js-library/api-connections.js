    
const walmartApiKey = 'yetbamnvuptfsnzehnsz99nr'
const googleApiKey = 'AIzaSyDiaHiIDgafsFhfwb1XQBtKETZ1zdlrP_o'
const shoppingListApiKey = 'q98ejf-fqwefj-8wefqw8w'

var userslists = [];
var list = {
    userid: null,
    listid: null,
    items: []

}
var item = {
    itemid: null,
    name: null,
    categoryid: null,
    category: null
}
var currentUser = {
    userid: null,
    email: null,
    password:null
}

//---------------------------ENDPOINT OBJECTS---------------------------------
var dataEnpoints = {
    createEndpoint: function (_endpoint,_action) {  
        return this.datahost + '/' + _endpoint + 'action=' + _action + '&' +'apiKey=' + this.apiKey
    },
    datahost: 'https://fe41a14.online-server.cloud',
    'apiKey': shoppingListApiKey,
    users: 'api.php?api=users&',
    lists: 'api.php?api=list&',
    listItems: 'api.php?api=listItems&'
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
        addedUserEventHandel(response)
    });
}
function data_LogInUser(_email, _password) {
    var endPoint = dataEnpoints.users
    var url = dataEnpoints.createEndpoint(endPoint, 'auth_user')
    var data = {
        email: _email,
        password: _password
    }
    $.ajax({
        type: "POST",
        url: url,
        data: data
    }).then(function (response) {
        isLoggedInEventHandel(response)
    });
}

//---------------------------PHP API LIST FUNCTIONS---------------------------
function data_AddList(_userid, _listname) {
    var endPoint = dataEnpoints.lists
    var url = dataEnpoints.createEndpoint(endPoint, 'add_list')
    var data = {
        userid: _userid,
        listname: _listname,
    }
    $.ajax({
        type: "POST",
        url: url,
        data: data
    }).then(function (response) {
        addListEventHandel(response)
    });
}

function data_RemoveList(_userid, _listid) {
    var endPoint = dataEnpoints.lists
    var url = dataEnpoints.createEndpoint(endPoint, 'remove_list')
    var data = {
        userid: _userid,
        listid: _listid
    }
    $.ajax({
        type: "POST",
        url: url,
        data: data
    }).then(function (response) {
        removeListEventHandel(response)
 });
}
//---------------------------PHP API ITEM FUNCTIONS---------------------------
function data_AddItem(_userid, _itemname, _categoryid, _categoryname, _listid) {
    var endPoint = dataEnpoints.listItems
    var url = dataEnpoints.createEndpoint(endPoint, 'add_item')
    var data = {
        userid: _userid,
        categoryid: _categoryid,
        categoryname: _categoryname,
        listid: _listid
    }
    $.ajax({
        type: "POST",
        url: url,
        data: data
    }).then(function (response) {
        addItemEventHandel(response)
    });
}
function data_GetListItems(_userid, _listid) {
    var endPoint = dataEnpoints.listItems
    var url = dataEnpoints.createEndpoint(endPoint, 'get_items')
    var data = {
        userid: _userid,
        listid: _listid
    }
    $.ajax({
        type: "POST",
        url: url,
        data: data
    }).then(function (response) {
        getItemsFromListEventHandel(response)
    });
}

function data_RemoveItem(_userid, _itemid, _listid) {
    var endPoint = dataEnpoints.listItems
    var url = dataEnpoints.createEndpoint(endPoint, 'remove_item')
    var data ={
        email: _email,
        password: _password
    }
    $.ajax({
        type: "POST",
        url: url,
        data: data
    }).then(function (response) {
        removeItemFromListEventHandel(response)
    });
}
//-----------------------------------EVENT HANDLERS-----------------------------
function addedUserEventHandel(_data) {
    $.event.trigger({
        type: "addedUser",
        message: _data
    });
}
function addItemEventHandel(_data) {
    $.event.trigger({
        type: "addedItem",
        message: _data
    });
}
function getItemsFromListEventHandel(_data) {
    $.event.trigger({
        type: "getItems",
        message: _data
    });
}
function removeItemFromListEventHandel(_data) {
    $.event.trigger({
        type: "removedItem",
        message: _data
    });
}
function removeListEventHandel(_data) {
    $.event.trigger({
        type: "removedList",
        message: _data
    });
}
function addListEventHandel(_data) {
    $.event.trigger({
        type: "addedList",
        message: _data
    });
}
function isLoggedInEventHandel(_data) {
     $.event.trigger({
         type: "isLoggedIn",
         message: _data
     });
}
//WALMART API EVENT HANDELERS--------------------------------------------------------------
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
function sortThatList() {
    if (list.items.length > 1) {
        list.items.sort(compareItems);
    }
}
function compareItems(itema, itemb) {
    if (itema.category < itemb.category) {
        return -1
    }
    if (itema.category > itemb.category) {
        return 1
    }
    return 0;
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
 currentUser.email = localStorage.get('email')
 currentUser.userid = localStorage.get('userid')
    if (!currentUser.userid) {
        data_AddUser('test2@live.com', '123456', '32792')
        
    } else {
        data_LogInUser('test2@live.com', '123456')
    }
    //EVENT HANDLERS 
    $(document).on('getWalmartItem', function (response) {
        console.log(response.message)
    })
    $(document).on('getWalmartItemSearch', function (response) {
        console.log(response.message)
    })
    $(document).on('addedUser', function (response) {
        console.log(response.message)

         data_LogInUser(response.message.email, '123456')
    })
    $(document).on('isLoggedIn', function (response) {
     var result = response.message
     console.log(result)
        currentUser.userid = result.userid
        currentUser.email = result.email
        localStorage.set('userid', currentUser.userid)
        localStorage.set('email', currentUser.email)

 })
    //FUNCTION TEST AREA
    walmart_SearchItems('dozen white eggs')

    walmart_GetItems({
        upc: '035000521019'
    })
    
    
    
   
    
})
