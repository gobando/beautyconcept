/*
 * File name: salon_details_view.dart
 * Last modified: 2022.02.26 at 14:50:11
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:shimmer/shimmer.dart';
import 'package:url_launcher/url_launcher.dart';

import '../../../../common/map.dart';
import '../../../../common/ui.dart';
import '../../global_widgets/circular_loading_widget.dart';
import '../../global_widgets/salon_availability_badge_widget.dart';
import '../controllers/salon_controller.dart';
import '../widgets/availability_hour_item_widget.dart';
import '../widgets/salon_til_widget.dart';

class SalonDetailsView extends GetView<SalonController> {
  @override
  Widget build(BuildContext context) {
    return Obx(() {
      return Wrap(
        children: [
          SalonTilWidget(
            title: Text("Description".tr, style: Get.textTheme.subtitle2),
            content: Obx(() {
              if (controller.salon.value.description == null) {
                return SizedBox();
              }
              return Ui.applyHtml(controller.salon.value.description, style: Get.textTheme.bodyText1);
            }),
          ),
          buildContactUs(),
          buildAddress(context),
          buildAvailabilityHours(),
        ],
      );
    });
  }

  Container buildContactUs() {
    return Container(
      margin: EdgeInsets.symmetric(horizontal: 20, vertical: 5),
      padding: EdgeInsets.symmetric(horizontal: 20, vertical: 10),
      decoration: Ui.getBoxDecoration(),
      child: Row(
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text("Contact us".tr, style: Get.textTheme.subtitle2),
                Text("If your have any question!".tr, style: Get.textTheme.caption),
              ],
            ),
          ),
          Wrap(
            spacing: 5,
            children: [
              MaterialButton(
                onPressed: () {
                  launch("tel:${controller.salon.value.mobileNumber}");
                },
                height: 44,
                minWidth: 44,
                padding: EdgeInsets.zero,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                color: Get.theme.colorScheme.secondary.withOpacity(0.2),
                child: Icon(
                  Icons.phone_android_outlined,
                  color: Get.theme.colorScheme.secondary,
                ),
                elevation: 0,
              ),
              MaterialButton(
                onPressed: () {
                  launch("tel:${controller.salon.value.phoneNumber}");
                },
                height: 44,
                minWidth: 44,
                padding: EdgeInsets.zero,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                color: Get.theme.colorScheme.secondary.withOpacity(0.2),
                child: Icon(
                  Icons.call_outlined,
                  color: Get.theme.colorScheme.secondary,
                ),
                elevation: 0,
              ),
              MaterialButton(
                onPressed: () {
                  controller.startChat();
                },
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                color: Get.theme.colorScheme.secondary.withOpacity(0.2),
                padding: EdgeInsets.zero,
                height: 44,
                minWidth: 44,
                child: Icon(
                  Icons.chat_outlined,
                  color: Get.theme.colorScheme.secondary,
                ),
                elevation: 0,
              ),
            ],
          )
        ],
      ),
    );
  }

  Container buildAddress(context) {
    return Container(
      margin: EdgeInsets.symmetric(horizontal: 20, vertical: 15),
      decoration: Ui.getBoxDecoration(),
      child: (controller.salon.value.address == null)
          ? Shimmer.fromColors(
              baseColor: Colors.grey.withOpacity(0.15),
              highlightColor: Colors.grey[200].withOpacity(0.1),
              child: Container(
                width: double.infinity,
                height: 220,
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.all(Radius.circular(10)),
                ),
              ),
            )
          : Stack(
              alignment: Alignment.bottomCenter,
              children: [
                ClipRRect(
                  borderRadius: BorderRadius.all(Radius.circular(10)),
                  child: MapsUtil.getStaticMaps(controller.salon.value.address.getLatLng()),
                ).paddingOnly(bottom: 50),
                Container(
                  padding: EdgeInsets.symmetric(horizontal: 20, vertical: 12),
                  decoration: BoxDecoration(
                    color: Get.theme.primaryColor,
                    borderRadius: BorderRadius.vertical(bottom: Radius.circular(10)),
                  ),
                  child: Row(
                    children: [
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text("Location".tr, style: Get.textTheme.subtitle2),
                            SizedBox(height: 5),
                            Text(controller.salon.value.address.address, style: Get.textTheme.caption),
                          ],
                        ),
                      ),
                      MaterialButton(
                        onPressed: () {
                          MapsUtil.openMapsSheet(context, controller.salon.value.address.getLatLng(), controller.salon.value.name);
                        },
                        height: 44,
                        minWidth: 44,
                        padding: EdgeInsets.zero,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                        color: Get.theme.colorScheme.secondary.withOpacity(0.2),
                        child: Icon(
                          Icons.directions_outlined,
                          color: Get.theme.colorScheme.secondary,
                        ),
                        elevation: 0,
                      ),
                    ],
                  ),
                ),
              ],
            ),
    );
  }

  SalonTilWidget buildAvailabilityHours() {
    return SalonTilWidget(
      title: Text("Availability".tr, style: Get.textTheme.subtitle2),
      content: (controller.salon.value.availabilityHours?.isEmpty ?? false)
          ? CircularLoadingWidget(height: 150)
          : ListView.separated(
              padding: EdgeInsets.zero,
              primary: false,
              shrinkWrap: true,
              itemCount: controller.salon.value.groupedAvailabilityHours().entries.length,
              separatorBuilder: (context, index) {
                return Divider(height: 16, thickness: 0.8);
              },
              itemBuilder: (context, index) {
                var _availabilityHour = controller.salon.value.groupedAvailabilityHours().entries.elementAt(index);
                var _data = controller.salon.value.getAvailabilityHoursData(_availabilityHour.key);
                return AvailabilityHourItemWidget(availabilityHour: _availabilityHour, data: _data);
              },
            ),
      actions: [
        SalonAvailabilityBadgeWidget(salon: controller.salon.value),
      ],
    );
  }
}
