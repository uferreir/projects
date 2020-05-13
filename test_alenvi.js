// exemple de bénéficiaire :
const customer1 = {
  _id: '1234567890',
  identity : { lastname : ‘X’ },
  subscriptions: [{
    _id: 'qwertyuio',
    service: {
      name: 'Aide a l'autonomie',
      saturdaySurcharge: 10,
      sundaySurcharge: 15,
    },
    versions: [
      { startDate: '2019-02-01T00:00:00', unitTTCPrice: 24 },
      { startDate: '2019-04-15T00:00:00', unitTTCPrice: 22 },
    ],
  }, {
    _id: 'asdfghjkl',
    service: { name: 'Ménage' },
    versions: [
      { startDate: '2019-02-01T00:00:00', unitTTCPrice: 22.3 },
      { startDate: '2019-04-15T00:00:00', unitTTCPrice: 21.7 },
    ],
  }],
  fundings: [{
    subscription: 'qwertyuio',
    percentage: 60,
    thirdPartyPayer: 'Hauts de Seine',
  }],
};

// on suppose un tableau comprenant tous les bénéficiaires (en l'occurence 1 pour l'exemple) :
const customerList = [customer1];

//question 1 :

function version(date, subscription) {
    var versions = subscription.versions;
    if (date < versions[0].startDate) {
        return 'erreur, la date est antérieure à la première version';
    } else {
        var i = 0;
        do {
            i++;
            if (date < versions[i].startDate) {
                break;
            }
        } while (i < versions.length);
        return i; // on part du principe que la première version s'appelle version 0
    }
}

//question 2, 3, 4 et 5

function Price(customerPrice, thirdPartyPrice, totalPrice) {
    this.customerPrice = customerPrice;
    this.thirdPartyPrice = thirdPartyPrice;
    this.totalPrice = totalPrice;
}

function interventionPrice(intervention) {
    var customer = (function(customerList) {
        for (var i = 0; i < customerList.length; i++) {
            if (customerList[i]._id == intervention.customer) {
            return customerList[i];
            }
        }
    })();

    var subscription = (function(customer){
        for (var i = 0; i < customer.subscriptions.length; i++) {
            if (customer.subscriptions[i]._id == intervention.subscription) {
            return customer.subscriptions[i];
            }
        }
    })();

    var version = version(intervention.endDate, subscription);
    var pricePerHour = subscription.versions[version].unitTTCPrice;
    var interventionTime = (intervention.endDate - intervention.startDate) * 1000 *3600; //convertir les millisecondes en heure
    var totalPrice = interventionTime * pricePerHour;

    if (intervention.endDate.getDay() == 0) {
        totalPrice += (subscription.sundaySurcharge/100) * totalPrice;
    }
    if (intervention.endDate.getDay() == 6) {
        totalPrice += (subscription.saturdaySurcharge/100) * totalPrice;
    }

    if (customer.fundings) {
        var i = 0;
        for (var i = 0; i < customer.fundings.length; i++) {
            if (customer.fundings[i].subscription == intervention.subscription) {
                return new Price(totalPrice - (totalPrice * (customer.funding.percentage/100)), totalPrince * (customer.funding.percentage/100), totalPrice);
            }
        }

    }
    return new Price(totalPrice,0,totalPrice);
}

//question 6

    function Bill(interventionPriceList, totalPrice) {
        this.interventionPriceList = interventionPriceList;
        this.totalPrice = totalPrice;
    }

function bill(interventionList) {

    var customerBill = new Bill([],0);
    var thirdPartyBill = new Bill([],0);

    for (var i = 0; i < interventionList.length; i++) {
        var tempPrice = interventionPrice(interventionList[i]);
            if (tempPrice.customerPrice != 0) {
                customerBill.interventionPriceList.push(tempPrice.customerPrice);
                customerBill.totalPrice += tempPrice.customerPrice;
            }
            if(tempPrice.thirdPartyPrice != 0) {
            thirdPartyBill.interventionPriceList.push(tempPrice.thirdPartyPrice);
            thirdPartyBill.totalPrice += tempPrice.thirdPartyPrice;
        }

    return [customerBill,thirdPartyBill];
}
