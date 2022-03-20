/*
 * File name: salon_repository.dart
 * Last modified: 2022.02.11 at 02:12:32
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:get/get.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';

import '../models/award_model.dart';
import '../models/e_service_model.dart';
import '../models/experience_model.dart';
import '../models/gallery_model.dart';
import '../models/review_model.dart';
import '../models/salon_model.dart';
import '../models/user_model.dart';
import '../providers/laravel_provider.dart';

class SalonRepository {
  LaravelApiClient _laravelApiClient;

  SalonRepository() {
    this._laravelApiClient = Get.find<LaravelApiClient>();
  }

  Future<List<Salon>> getRecommended() {
    return _laravelApiClient.getRecommendedSalons();
  }

  Future<List<Salon>> getNearSalons(LatLng latLng, LatLng areaLatLng) {
    return _laravelApiClient.getNearSalons(latLng, areaLatLng);
  }

  Future<Salon> get(String salonId) {
    return _laravelApiClient.getSalon(salonId);
  }

  Future<List<Review>> getReviews(String salonId) {
    return _laravelApiClient.getSalonReviews(salonId);
  }

  Future<List<Gallery>> getGalleries(String salonId) {
    return _laravelApiClient.getSalonGalleries(salonId);
  }

  Future<List<Award>> getAwards(String salonId) {
    return _laravelApiClient.getSalonAwards(salonId);
  }

  Future<List<Experience>> getExperiences(String salonId) {
    return _laravelApiClient.getSalonExperiences(salonId);
  }

  Future<List<EService>> getEServices(String salonId, List<String> categories, {int page}) {
    return _laravelApiClient.getSalonEServices(salonId, categories, page);
  }

  Future<List<User>> getEmployees(String salonId) {
    return _laravelApiClient.getSalonEmployees(salonId);
  }

  Future<List<EService>> getPopularEServices(String salonId, List<String> categories, {int page}) {
    return _laravelApiClient.getSalonPopularEServices(salonId, categories, page);
  }

  Future<List<EService>> getMostRatedEServices(String salonId, List<String> categories, {int page}) {
    return _laravelApiClient.getSalonMostRatedEServices(salonId, categories, page);
  }

  Future<List<EService>> getAvailableEServices(String salonId, List<String> categories, {int page}) {
    return _laravelApiClient.getSalonAvailableEServices(salonId, categories, page);
  }

  Future<List<EService>> getFeaturedEServices(String salonId, List<String> categories, {int page}) {
    return _laravelApiClient.getSalonFeaturedEServices(salonId, categories, page);
  }

  Future<List> getAvailabilityHours(String eProviderId, DateTime date, String employeeId) {
    return _laravelApiClient.getAvailabilityHours(eProviderId, date, employeeId);
  }
}
