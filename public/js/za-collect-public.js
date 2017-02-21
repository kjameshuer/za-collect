  var CollectionMaker = (function($) {

      var instance;

      function init() {
          /**
           * Finds product images, removes parent and adds the collection number to
           * a data attribute.
           * @return null
           */
          function addCollectionDataToProductImages() {
              $('.zazzle-product-image').unwrap();

              $('.za-collect-collection-holder-class').each(function(it) {
                  $(this).find('.zazzle-product-image').each(function(i, e) {
                      $(e).attr('data-collect-num', it);
                  });
              });
          }
          /**
           * Prepares and populates collections with lightbox products
           * 
           * @return null
           */
          function initializeCollections() {
              $('div.za-collect[id^="za-collect-"]').each(function(itt, val) {

                  var collection = $(this).attr('zacollectionnum');
                  var collectionProducts = window['theProducts_' + collection];

                  var thisCollection = new CollectionMaker(collectionProducts, collection);

                  var prodLen = thisCollection.products.length;

                  for (var i = 0; i < prodLen; i++) {
                      thisCollection.products[i]['price'] = $('<textarea/>').html(getProductPrice(thisCollection.products[i])).text();
                      createProductGridProduct(thisCollection.products[i], thisCollection, i, itt);
                      createLightBoxProduct(thisCollection.products[i], thisCollection);
                  }

                  $('#za-collection-holder-' + collection).css('display', 'block')
                      .animate({
                          'opacity': 1
                      }, 550);

              });
          }
          /**
           * Generates and appends DOM element for the product grid displayed on the blog
           * page.
           * @param {Object} prodObject
           * @param {Number} i
           *  @param {Number} itt
           * @param {Object} productSet
           * @return null
           */
          function createProductGridProduct(prodObject, productSet, i, itt) {

              var thisProduct = $('.za-collection-' + productSet.collectionNum + '-product-' + i + '[data-collect-num=' + itt + ']');

              productSet.gridProductArray.push(prodObject);

              thisProduct.bind('vclick', function(e) {
                  e.preventDefault();
                  showMeLightBox(e.target, productSet, itt);
              });

          }
          /**
          * Generates and appends DOM element for the products featured in the lightbox.
          * @param {Object} product

          * @param {Object} thisProductSet
          * @return null
          */
          function createLightBoxProduct(product, thisProductSet) {

              var productHref = getProductHref(product, thisProductSet);
              var authorHref = getAuthorHref(product, thisProductSet);
              var newWindow = '_self';

              if (zaCollectOptions.openNewWindow > 0) {
                  newWindow = '_blank';
              }

              //container

              var lightboxContainer = $('<div/>').addClass('za-collect-current-product');

              //title
              var productLink = $('<a/>').attr({
                      href: productHref,
                      rel: 'nofollow',
                      target: newWindow
                  })
                  .text($('<textarea/>').html(product.title).text());
              var productTitle = $('<h3/>').addClass('zacollect-lightbox-product-title')
                  .append(productLink);

              //author


              var authorLink = $('<a/>').addClass('zacollect-lightbox-author-link')
                  .attr({
                      href: authorHref,
                      rel: 'nofollow',
                      target: newWindow
                  })
                  .text($('<textarea/>').html(product.author).text());

              var productAuthor = $('<p/>').addClass('zacollect-lightbox-product-author')
                  .text('Designed by: ')
                  .append(authorLink);

              //price

              var productPrice = $('<p/>').addClass('zacollect-lightbox-product-price')
                  .text(product.price);

              //description

              var descriptionText = $('<textarea/>').html(product.description).text();

              var productDescription = $('<p/>').addClass('zacollect-lightbox-product-description')
                  .text(descriptionText);

              //images

              var productImage = $('<img/>').addClass('zacollect-lightbox-product-image')
                  .attr('src', product.image);



              //buyButton


              var btnText = $('<div/>').text($('<textarea/>').html(zaCollectOptions.buyNowText).text()).html();
              if (!btnText || btnText === 'null') {
                  btnText = "Buy Now";
              }


              var buyBtn = $('<a/>').addClass('zacollect-lightbox-buy-btn')
                  .text(btnText)
                  .attr({
                      href: productHref,
                      rel: 'nofollow',
                      target: newWindow
                  }).css({
                      background: zaCollectOptions.accentColor,
                      color: zaCollectOptions.accentTextColor
                  });


              //STRUCTURE

              var productImageDiv = $('<div/>').addClass('zacollect-product-image-holder')
                  .append(productImage);

              var productTextDiv = $('<div/>').addClass('zacollect-product-text-holder')
                  .append(productTitle, productPrice, productAuthor, productDescription, buyBtn);

              lightboxContainer.append(productImageDiv, productTextDiv);

              thisProductSet.lightBoxProductArray.push(lightboxContainer);

          }

          /**
           * Adds event handlers for window resize.
           * 
           * @return null
           */

          function addEventHandlers() {
              var resizeInt;


              window.addEventListener("resize", function() {

                  clearTimeout(resizeInt);
                  resizeInt = setTimeout(function() {

                      var height = window.innerHeight,
                          width = window.innerWidth,
                          isPortrait = checkIfPortrait(height, width);

                      if (typeof $('#za-collect-lightbox-container') !== 'undefined') {
                          var container = $('#za-collect-lightbox-container');
                          container.find('.zacollect-lightbox-product-description').show();
                          checkWinHeightResize(container, isPortrait, false);
                      }
                  }, 150);

              });
          }
          /**
          * Attaches the referral ID and tracking code to the product link
          * if the options have been set.
          * @param {Object} product

          * @param {Object} thisProductSet
          * @return {String}
          */
          function getProductHref(product, thisProductSet) {

              var returnLink = product.link;
              if (zaCollectOptions.refId) {
                  returnLink += '?rf=' + zaCollectOptions.refId;

                  if (thisProductSet.tracking) {
                      returnLink += '&tc=' + thisProductSet.tracking;
                  }

              }
              return returnLink;
          }
          /**
          * Attaches the referral ID and tracking code to the author link
          * if the options have been set.
          * @param {Object} product

          * @param {Object} thisProductSet
          * @return {String}
          */
          function getAuthorHref(product, thisProductSet) {

              var authorLinkHref = 'http://www.zazzle.com/' + product.author;
              if (zaCollectOptions.refId) {
                  authorLinkHref += '?rf=' + zaCollectOptions.refId;
                  if (thisProductSet.tracking) {
                      authorLinkHref += '&tc=' + thisProductSet.tracking;
                  }
              }
              return authorLinkHref;
          }
          /**
           * Returns the price of the provided product
           * @param {Object} prod
           * @return {String}
           */
          function getProductPrice(prod) {
              return $($(prod.content).find('.ZazzleCollectionItemCellProduct-price')[0]).text();
          }

          /**
           * Returns the price of the provided product
           * @param {String} badString
           * @return {String}
           */
          function stripHTML(badString) {
              var container = document.createElement('div');
              var text = document.createTextNode(badString);
              container.appendChild(text);

              return container.innerHTML;
          }
          /**
           * Generates lightbox and populates it with matching product info
           * @param {Object} el
           * @param {Object} thisProductSet
           * @param {Number} itt
           * @return null
           */
          function showMeLightBox(el, thisProductSet, itt) {

              var className = el.className;

              var arrayPos = parseInt(className.replace('zazzle-product-image za-collection-' + thisProductSet.collectionNum + '-product-', ''));
              thisProductSet.curArrayPosition = arrayPos;

              var product = thisProductSet.products[thisProductSet.curArrayPosition];

              var lightbox = $('<div/>').attr('id', 'za-collect-lightbox')
                  .click(function(e) {

                      if ($(e.target).parent()[0].nodeName === "BODY") {

                          removeCurrentProductFromLightBox();
                          removeLightBoxAndContents();
                      }
                  });

              var closeBtn = $('<div/>')
                  .attr({
                      'id': 'za-collect-lightbox-close'
                  })
                  .css({
                      maxWidth: '50px'
                  })
                  .on("click", function() {
                      removeCurrentProductFromLightBox();
                      removeLightBoxAndContents();

                  });
              var xIcon = $('<span/>').addClass('dashicons dashicons-dismiss za-collect-lightbox-close-btn')
                  .css({
                      'font-size': '30px',
                      color: zaCollectOptions.accentColor
                  });
              closeBtn.append(xIcon);
              //   lightbox.append(closeBtn);

              var nextBtn = $('<div/>').append('<span style="font-size:50px" class="dashicons \n\
                    dashicons-arrow-right-alt2"></span>')
                  .attr('id', 'za-collect-lightbox-next')
                  .on("click", function(e) {

                      moveLightBoxSlideShow(true, thisProductSet);
                      removeCurrentProductFromLightBox();
                      addProductInfoToLightBox(lightbox,
                          thisProductSet.products[thisProductSet.curArrayPosition],
                          thisProductSet);
                  });

              var prevBtn = $('<div/>').append('<span style="font-size:50px" \n\
                class="dashicons dashicons-arrow-left-alt2"></span>')
                  .attr('id', 'za-collect-lightbox-prev')
                  .on("click", function(e) {

                      moveLightBoxSlideShow(false, thisProductSet);
                      removeCurrentProductFromLightBox();
                      addProductInfoToLightBox(lightbox,
                          thisProductSet.products[thisProductSet.curArrayPosition],
                          thisProductSet);
                  });

              var container = $('<div/>')
                  .attr('id', 'za-collect-lightbox-container')
                  .append(closeBtn);

              lightbox.append(container, nextBtn, prevBtn);

              $('body').append(lightbox);

              lightbox.animate({
                  opacity: 1
              }, 350, function() {

                  $('body').addClass('za-collect-no-scroll').height($(window).height());
                  addProductInfoToLightBox(lightbox, product, thisProductSet);
              });
          }
          /**
           * Increases or decreases the curArrayPosition variable which is used to determine
           * the product shown in the lightbox
           * @param {Boolean} movingForward
           * @param {Object} thisProductSet
           *
           * @return null
           */
          function moveLightBoxSlideShow(movingForward, thisProductSet) {

              if (thisProductSet.curArrayPosition <= thisProductSet.products.length - 1 && thisProductSet.curArrayPosition >= 0) {
                  if (movingForward) {
                      thisProductSet.curArrayPosition++;
                      checkArrayPositionAndReset(movingForward, thisProductSet);
                  } else {
                      thisProductSet.curArrayPosition--;
                      checkArrayPositionAndReset(movingForward, thisProductSet);
                  }
              }

          }
          /**
           * Resets curArrayPosition variable if user hits beginning or end of array while
           * cycling through products during lightbox mode
           * 
           * @param {Boolean} movingForward
           * @param {Object} thisProductSet
           *
           * @return null
           */
          function checkArrayPositionAndReset(movingForward, thisProductSet) {
              if (movingForward) {
                  if (thisProductSet.curArrayPosition >= thisProductSet.products.length) {
                      thisProductSet.curArrayPosition = 0;
                  }
              } else {
                  if (thisProductSet.curArrayPosition < 0) {
                      thisProductSet.curArrayPosition = thisProductSet.products.length - 1;
                  }
              }
          }
          /**
           * Add the product element to the DOM in lightbox
           * @param {Object} box
           * @param {Object} product
           * @param {Object} thisProductSet
           *
           * @return null
           */
          function addProductInfoToLightBox(box, product, thisProductSet) {

              var container = $('#za-collect-lightbox-container');
              var thisProduct = thisProductSet.lightBoxProductArray[thisProductSet.curArrayPosition];

              container.append(thisProduct);
              box.append(container);
              var height = window.innerHeight,
                  width = window.innerWidth,
                  isPortrait = checkIfPortrait(height, width);
              checkWinHeightResize(container, isPortrait, false);

              thisProductSet.lightBoxProductArray[thisProductSet.curArrayPosition].animate({

                  opacity: 1
              }, 350, function() {
                  $(this).addClass('lightbox-open');
              });
          }
          /**
           * Sets or adjusts the layout of the product container in lightbox for viewing in portrait
           * or landscape. 
           * 
           * @param {Object} container
           * @param {Boolean} isPortrait
           * @param {Boolean} secondTime
           * @return null
           */
          function checkWinHeightResize(container, isPortrait, secondTime) {


              var winHeight = $(window).height();
              //   var contHeight = (container.height() + 30);

              $(container).find('.zacollect-lightbox-product-description').show();
              var containerNewHeight = container.height();
              var prodImage = $(container).find('.zacollect-lightbox-product-image');
              var prodImageHeight = prodImage.height();
              if (containerNewHeight <= winHeight) {
                  var marHeight = Math.floor((winHeight - containerNewHeight) / 2);
                  container.css('margin-top', marHeight);

//                  if (!isPortrait) {
//                      var imgContDiff = (containerNewHeight - (prodImageHeight + 52));
//
//                      if (imgContDiff > 0) {
//
//                          prodImage.css('maxWidth', prodImageHeight + imgContDiff + 'px');
//                      }
//                  }

              } else {
                  $(container).find('.zacollect-lightbox-product-description').hide();
                  var diff = containerNewHeight - winHeight;

                  if (isPortrait) {

                   //   prodImage.height(prodImageHeight - diff + 'px');
                  //    var prodImageHolder = $(container).find('.zacollect-product-image-holder');
                    //  var prodImageHolderHeight = prodImageHolder.height();
                   //   prodImageHolder.height(prodImageHolderHeight - diff + 'px');

                      if (!secondTime) {
                          checkWinHeightResize(container, isPortrait, true);
                      }
                  } else {

                      $(container).find('.zacollect-lightbox-product-description').hide();

                      if (!secondTime) {
                          checkWinHeightResize(container, isPortrait, true);
                      }
                  }
              }



          }

          /**
           * Removes product info from the lightbox container
           *
           * @return null
           */
          function removeCurrentProductFromLightBox() {

              $('.za-collect-current-product').remove();
          }

          /**
           * Removes lightbox from DOM
           *
           * @return null
           */
          function removeLightBoxAndContents() {
              $('body').removeClass('za-collect-no-scroll').css('height', '100%');
              $('#za-collect-lightbox').animate({
                  opacity: 0
              }, 350, function() {
                  $(this).remove();
              });

          }
          /**
           * Checks if content is being viewed in Portrait or 
           * Landscape
           * 
           * @param {Number} h
           * @param {Number} w
           *
           * @return {Bool}
           */
          function checkIfPortrait(h, w) {

              if (h > w && w < 700) {
                  return true;
              } else {
                  return false;
              }

          }

          function CollectionMaker(collectionObject, collection) {

              this.collectionNum = collection;
              this.title = collectionObject.products.collecctionTitle;
              this.products = collectionObject.products.products;
              this.lightBoxProductArray = [];
              this.gridProductArray = [];
              this.curArrayPosition = 0;
              this.tracking = collectionObject.tracking;


          }
          return {
              go: function() {
                  addCollectionDataToProductImages();
                  initializeCollections();
                  addEventHandlers();
              }
          };

      };


      return {
          getInstance: function() {
              if (!instance) {

                  instance = init();

              }
              return instance;
          }
      };

  })(jQuery);

  (function() {
      'use strict';
      var newColl = CollectionMaker.getInstance();
      newColl.go();


  })();