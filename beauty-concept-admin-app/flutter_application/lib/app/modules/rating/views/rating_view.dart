/*
 * File name: rating_view.dart
 * Last modified: 2022.02.10 at 15:34:38
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../../../common/ui.dart';
import '../../global_widgets/block_button_widget.dart';
import '../../global_widgets/text_field_widget.dart';
import '../controllers/rating_controller.dart';

class RatingView extends GetView<RatingController> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(
          "Leave a Review".tr,
          style: Get.textTheme.headline6,
        ),
        centerTitle: true,
        backgroundColor: Colors.transparent,
        automaticallyImplyLeading: false,
        leading: new IconButton(
          icon: new Icon(Icons.arrow_back_ios, color: Get.theme.hintColor),
          onPressed: () => Get.back(),
        ),
        elevation: 0,
      ),
      bottomNavigationBar: BlockButtonWidget(
          text: Text(
            "Submit Review".tr,
            style: Get.textTheme.headline6.merge(TextStyle(color: Get.theme.primaryColor)),
          ),
          color: Get.theme.colorScheme.secondary,
          onPressed: () {
            controller.addReview();
          }).marginSymmetric(vertical: 20, horizontal: 20),
      body: ListView(
        primary: true,
        shrinkWrap: true,
        padding: EdgeInsets.symmetric(vertical: 20),
        children: [
          Container(
              margin: EdgeInsets.symmetric(horizontal: 20),
              width: Get.width,
              decoration: Ui.getBoxDecoration(),
              child: Obx(
                () => Column(
                  crossAxisAlignment: CrossAxisAlignment.center,
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    ClipRRect(
                      borderRadius: BorderRadius.vertical(top: Radius.circular(10)),
                      child: CachedNetworkImage(
                        height: 180,
                        width: double.infinity,
                        fit: BoxFit.cover,
                        imageUrl: controller.review.value.booking.salon.firstImageUrl,
                        placeholder: (context, url) => Image.asset(
                          'assets/img/loading.gif',
                          fit: BoxFit.cover,
                          width: double.infinity,
                          height: 160,
                        ),
                        errorWidget: (context, url, error) => Icon(Icons.error_outline),
                      ),
                    ),
                    SizedBox(height: 20),
                    Text(
                      controller.review.value.booking.salon.name,
                      style: Get.textTheme.headline6,
                      textAlign: TextAlign.center,
                    ),
                    SizedBox(height: 20),
                    Text(
                      "Click on the stars to rate this salon services".tr,
                      style: Get.textTheme.caption,
                    ),
                    SizedBox(height: 6),
                    Obx(() {
                      return Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: List.generate(5, (index) {
                          return InkWell(
                            onTap: () {
                              controller.review.update((val) {
                                val.rate = (index + 1).toDouble();
                              });
                            },
                            child: index < controller.review.value.rate
                                ? Icon(Icons.star, size: 40, color: Color(0xFFFFB24D))
                                : Icon(Icons.star_border, size: 40, color: Color(0xFFFFB24D)),
                          );
                        }),
                      );
                    }),
                    SizedBox(height: 30)
                  ],
                ),
              )),
          TextFieldWidget(
            labelText: "Write your review".tr,
            hintText: "Tell us somethings about this salon services".tr,
            iconData: Icons.description_outlined,
            onChanged: (text) {
              controller.review.update((val) {
                val.review = text;
              });
            },
          ),
        ],
      ),
    );
  }
}
