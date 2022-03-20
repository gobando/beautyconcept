/*
 * File name: salon_reviews_view.dart
 * Last modified: 2022.02.10 at 17:47:27
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../../../common/ui.dart';
import '../../global_widgets/circular_loading_widget.dart';
import '../controllers/salon_reviews_controller.dart';
import '../widgets/review_item_widget.dart';
import '../widgets/salon_til_widget.dart';

class SalonReviewsView extends GetView<SalonReviewsController> {
  @override
  Widget build(BuildContext context) {
    return SalonTilWidget(
      title: Text("Reviews & Ratings".tr, style: Get.textTheme.subtitle2),
      content: Column(
        children: [
          Text(controller.salon.value.rate.toString(), style: Get.textTheme.headline1),
          Wrap(
            children: Ui.getStarsList(controller.salon.value.rate, size: 32),
          ),
          Text(
            "Reviews (%s)".trArgs([controller.salon.value.totalReviews.toString()]),
            style: Get.textTheme.caption,
          ).paddingOnly(top: 10),
          Divider(height: 35, thickness: 1.3),
          Obx(() {
            if (controller.reviews.isEmpty) {
              return CircularLoadingWidget(height: 100);
            }
            return ListView.separated(
              padding: EdgeInsets.all(0),
              itemBuilder: (context, index) {
                return ReviewItemWidget(review: controller.reviews.elementAt(index));
              },
              separatorBuilder: (context, index) {
                return Divider(height: 35, thickness: 1.3);
              },
              itemCount: controller.reviews.length,
              primary: false,
              shrinkWrap: true,
            );
          }),
        ],
      ),
      actions: [
        // TODO view all reviews
      ],
    );
  }
}
