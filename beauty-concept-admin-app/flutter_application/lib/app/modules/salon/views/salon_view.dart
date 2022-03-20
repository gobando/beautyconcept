/*
 * File name: salon_view.dart
 * Last modified: 2022.02.13 at 15:44:07
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:cached_network_image/cached_network_image.dart';
import 'package:carousel_slider/carousel_slider.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../../../common/ui.dart';
import '../../../models/media_model.dart';
import '../../../models/salon_model.dart';
import '../../../providers/laravel_provider.dart';
import '../../../routes/app_routes.dart';
import '../../global_widgets/block_button_widget.dart';
import '../../global_widgets/circular_loading_widget.dart';
import '../../global_widgets/tab_bar_widget.dart';
import '../controllers/salon_controller.dart';
import '../controllers/salon_e_services_controller.dart';
import '../widgets/e_services_filter_bottom_sheet_widget.dart';
import '../widgets/salon_title_bar_widget.dart';

class SalonView extends GetView<SalonController> {
  @override
  Widget build(BuildContext context) {
    return Obx(() {
      var _salon = controller.salon.value;
      if (!_salon.hasData) {
        return Scaffold(
          body: CircularLoadingWidget(height: Get.height),
        );
      } else {
        return Scaffold(
          floatingActionButton: controller.currentIndex.value == 1
              ? new FloatingActionButton(
                  child: new Icon(Icons.sort, size: 24, color: Get.theme.primaryColor),
                  onPressed: () => {
                    Get.bottomSheet(
                      EServicesFilterBottomSheetWidget(),
                      isScrollControlled: true,
                    ),
                  },
                  backgroundColor: Get.theme.colorScheme.secondary,
                )
              : null,
          bottomNavigationBar: controller.currentIndex.value == 1 ? buildBottomWidget() : null,
          body: RefreshIndicator(
              onRefresh: () async {
                Get.find<LaravelApiClient>().forceRefresh();
                controller.refreshSalon(showMessage: true);
                Get.find<LaravelApiClient>().unForceRefresh();
              },
              child: CustomScrollView(
                primary: true,
                shrinkWrap: false,
                physics: AlwaysScrollableScrollPhysics(),
                slivers: <Widget>[
                  SliverAppBar(
                    backgroundColor: Theme.of(context).scaffoldBackgroundColor,
                    expandedHeight: 360,
                    elevation: 0,
                    floating: true,
                    iconTheme: IconThemeData(color: Theme.of(context).primaryColor),
                    centerTitle: true,
                    automaticallyImplyLeading: false,
                    leading: new IconButton(
                      icon: new Icon(Icons.arrow_back_ios, color: Get.theme.hintColor),
                      onPressed: () => {Get.back()},
                    ),
                    bottom: buildSalonTitleBarWidget(_salon),
                    flexibleSpace: FlexibleSpaceBar(
                      collapseMode: CollapseMode.pin,
                      background: Obx(() {
                        return Stack(
                          alignment: AlignmentDirectional.bottomCenter,
                          children: <Widget>[
                            buildCarouselSlider(_salon),
                            buildCarouselBullets(_salon),
                          ],
                        ).paddingOnly(bottom: 70);
                      }),
                    ),
                  ),
                  SliverToBoxAdapter(
                    child: controller.currentPage,
                  )
                ],
              )),
        );
      }
    });
  }

  CarouselSlider buildCarouselSlider(Salon _salon) {
    return CarouselSlider(
      options: CarouselOptions(
        autoPlay: true,
        autoPlayInterval: Duration(seconds: 7),
        height: 360,
        viewportFraction: 1.0,
        onPageChanged: (index, reason) {
          controller.currentSlide.value = index;
        },
      ),
      items: _salon.images.map((Media media) {
        return Builder(
          builder: (BuildContext context) {
            return Hero(
              tag: controller.heroTag + _salon.id,
              child: CachedNetworkImage(
                width: double.infinity,
                height: 360,
                fit: BoxFit.cover,
                imageUrl: media.url,
                placeholder: (context, url) => Image.asset(
                  'assets/img/loading.gif',
                  fit: BoxFit.cover,
                  width: double.infinity,
                ),
                errorWidget: (context, url, error) => Icon(Icons.error_outline),
              ),
            ).paddingOnly(bottom: 40);
          },
        );
      }).toList(),
    );
  }

  Container buildCarouselBullets(Salon _salon) {
    return Container(
      margin: EdgeInsets.symmetric(vertical: 100, horizontal: 20),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: _salon.images.map((Media media) {
          return Container(
            width: 20.0,
            height: 5.0,
            margin: EdgeInsets.symmetric(vertical: 20.0, horizontal: 2.0),
            decoration: BoxDecoration(
                borderRadius: BorderRadius.all(
                  Radius.circular(10),
                ),
                color: controller.currentSlide.value == _salon.images.indexOf(media) ? Get.theme.hintColor : Get.theme.primaryColor.withOpacity(0.4)),
          );
        }).toList(),
      ),
    );
  }

  SalonTitleBarWidget buildSalonTitleBarWidget(Salon _salon) {
    return SalonTitleBarWidget(
      title: Column(
        mainAxisAlignment: MainAxisAlignment.spaceAround,
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          Row(
            children: [
              Expanded(
                child: Text(
                  _salon.name ?? '',
                  style: Get.textTheme.headline5.merge(TextStyle(height: 1.1)),
                  maxLines: 2,
                  softWrap: true,
                  overflow: TextOverflow.fade,
                ),
              ),
              Container(
                child: Text(_salon.salonLevel?.name?.tr ?? ' . . . ',
                    maxLines: 1,
                    style: Get.textTheme.bodyText2.merge(
                      TextStyle(color: Get.theme.colorScheme.secondary, height: 1.4, fontSize: 10),
                    ),
                    softWrap: false,
                    textAlign: TextAlign.center,
                    overflow: TextOverflow.fade),
                decoration: BoxDecoration(
                  color: Get.theme.colorScheme.secondary.withOpacity(0.2),
                  borderRadius: BorderRadius.circular(8),
                ),
                padding: EdgeInsets.symmetric(horizontal: 10, vertical: 5),
              ),
            ],
          ),
          Row(
            children: [
              Expanded(
                child: Wrap(
                  crossAxisAlignment: WrapCrossAlignment.end,
                  children: List.from(Ui.getStarsList(_salon.rate ?? 0))
                    ..addAll([
                      SizedBox(width: 5),
                      Text(
                        "Reviews (%s)".trArgs([_salon.totalReviews.toString()]),
                        style: Get.textTheme.caption,
                      ),
                    ]),
                ),
              ),
              Text(
                Ui.getDistance(_salon.distance),
                style: Get.textTheme.caption,
              ),
            ],
          ),
        ],
      ),
      tabBar: _salon.description == null
          ? TabBarLoadingWidget()
          : TabBarWidget(
              initialSelectedId: 0,
              tag: 'salon',
              tabs: [
                ChipWidget(
                  tag: 'salon',
                  text: "Details".tr,
                  id: 0,
                  onSelected: (id) {
                    print(id);
                    controller.changePage(id);
                  },
                ),
                ChipWidget(
                  tag: 'salon',
                  text: "Services".tr,
                  id: 1,
                  onSelected: (id) {
                    print(id);
                    controller.changePage(id);
                  },
                ),
                ChipWidget(
                  tag: 'salon',
                  text: "Galleries".tr,
                  id: 2,
                  onSelected: (id) {
                    print(id);
                    controller.changePage(id);
                  },
                ),
                ChipWidget(
                  tag: 'salon',
                  text: "Reviews".tr,
                  id: 3,
                  onSelected: (id) {
                    print(id);
                    controller.changePage(id);
                  },
                ),
                ChipWidget(
                  tag: 'salon',
                  text: "Awards".tr,
                  id: 4,
                  onSelected: (id) {
                    print(id);
                    controller.changePage(id);
                  },
                ),
                ChipWidget(
                  tag: 'salon',
                  text: "Experiences".tr,
                  id: 5,
                  onSelected: (id) {
                    print(id);
                    controller.changePage(id);
                  },
                )
              ],
            ),
    );
  }

  Widget buildBottomWidget() {
    var _booking = Get.find<SalonEServicesController>().booking.value;
    return Container(
      padding: EdgeInsets.symmetric(vertical: 20),
      decoration: BoxDecoration(
        color: Get.theme.primaryColor,
        borderRadius: BorderRadius.all(Radius.circular(20)),
        boxShadow: [
          BoxShadow(color: Get.theme.focusColor.withOpacity(0.1), blurRadius: 10, offset: Offset(0, -5)),
        ],
      ),
      child: Row(
        children: [
          Expanded(
            child: BlockButtonWidget(
                text: Container(
                  height: 24,
                  alignment: Alignment.center,
                  child: Text(
                    "Place a Booking".tr,
                    textAlign: TextAlign.center,
                    style: Get.textTheme.headline6.merge(
                      TextStyle(color: Get.theme.primaryColor),
                    ),
                  ),
                ),
                color: Get.theme.colorScheme.secondary,
                onPressed: _booking.eServices.isEmpty
                    ? null
                    : () {
                        print(_booking);
                        Get.toNamed(Routes.BOOK_E_SERVICE, arguments: {'booking': _booking});
                      }),
          ),
          SizedBox(width: 20),
          Wrap(
            direction: Axis.vertical,
            spacing: 2,
            children: [
              Text(
                "Subtotal".tr,
                style: Get.textTheme.caption,
              ),
              Ui.getPrice(_booking.getSubtotal(), style: Get.textTheme.headline6),
            ],
          )
        ],
      ).paddingSymmetric(horizontal: 20),
    );
  }
}
