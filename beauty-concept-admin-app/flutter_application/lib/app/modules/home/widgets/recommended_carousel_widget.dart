/*
 * File name: recommended_carousel_widget.dart
 * Last modified: 2022.02.11 at 01:33:14
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../../../common/ui.dart';
import '../../../routes/app_routes.dart';
import '../../global_widgets/salon_availability_badge_widget.dart';
import '../controllers/home_controller.dart';
import 'salon_main_thumb_widget.dart';
import 'salon_thumbs_widget.dart';

class RecommendedCarouselWidget extends GetWidget<HomeController> {
  @override
  Widget build(BuildContext context) {
    return Container(
      height: 375,
      child: Obx(() {
        return ListView.builder(
            padding: EdgeInsets.only(bottom: 18),
            primary: false,
            shrinkWrap: false,
            scrollDirection: Axis.horizontal,
            itemCount: controller.salons.length,
            itemBuilder: (_, index) {
              var _salon = controller.salons.elementAt(index);
              return GestureDetector(
                onTap: () {
                  Get.toNamed(Routes.SALON, arguments: {'salon': _salon, 'heroTag': 'recommended_carousel'});
                },
                child: Container(
                  width: 280,
                  margin: EdgeInsetsDirectional.only(end: 20, start: index == 0 ? 20 : 0, top: 20, bottom: 10),
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.all(Radius.circular(10)),
                    boxShadow: [
                      BoxShadow(color: Get.theme.focusColor.withOpacity(0.1), blurRadius: 10, offset: Offset(0, 5)),
                    ],
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      SalonMainThumbWidget(salon: _salon),
                      SizedBox(height: 2),
                      SalonThumbsWidget(salon: _salon),
                      Container(
                        padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 15),
                        height: 110,
                        width: double.infinity,
                        decoration: BoxDecoration(
                          color: Get.theme.primaryColor,
                          borderRadius: BorderRadius.only(bottomLeft: Radius.circular(10), bottomRight: Radius.circular(10)),
                        ),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.stretch,
                          mainAxisAlignment: MainAxisAlignment.spaceAround,
                          children: [
                            Text(
                              _salon.name ?? '',
                              maxLines: 2,
                              style: Get.textTheme.bodyText2.merge(TextStyle(color: Get.theme.hintColor)),
                            ),
                            Text(
                              Ui.getDistance(_salon.distance),
                              style: Get.textTheme.bodyText1,
                            ),
                            SizedBox(height: 8),
                            Wrap(
                              spacing: 5,
                              alignment: WrapAlignment.spaceBetween,
                              crossAxisAlignment: WrapCrossAlignment.center,
                              direction: Axis.horizontal,
                              children: [
                                Wrap(
                                  children: Ui.getStarsList(_salon.rate),
                                ),
                                SalonAvailabilityBadgeWidget(salon: _salon),
                              ],
                            )
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              );
            });
      }),
    );
  }
}
