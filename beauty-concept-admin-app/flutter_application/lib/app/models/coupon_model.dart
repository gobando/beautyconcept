/*
 * File name: coupon_model.dart
 * Last modified: 2022.02.12 at 00:59:13
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'parents/model.dart';

class Coupon extends Model {
  String id;
  String code;
  double discount;
  String discountType;
  double value;

  Coupon({this.id, this.code, this.discount, this.discountType, this.value});

  Coupon.fromJson(Map<String, dynamic> json) {
    super.fromJson(json);
    code = stringFromJson(json, 'code');
    discount = doubleFromJson(json, 'discount');
    discountType = stringFromJson(json, 'discount_type');
    value = doubleFromJson(json, 'value');
  }

  @override
  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = Map<String, dynamic>();
    data['id'] = this.id;
    data['code'] = this.code;
    data['discount'] = this.discount;
    data['discount_type'] = this.discountType;
    data['value'] = this.value;
    return data;
  }
}
