var my_handlers = {
  // fill province
  fill_provinces: function () {
    //selected region
    var region_code = $(this).val();

    // set selected text to input
    var region_text = $(this).find("option:selected").text();
    let region_input = $("#region-text");
    region_input.val(region_text);
    //clear province & city & barangay input
    $("#province-text").val("");
    $("#city-text").val("");
    $("#barangay-text").val("");

    //province
    let dropdown = $("#province");
    dropdown.empty();
    dropdown.append('<option selected="true" disabled>Choose State/Province</option>');
    dropdown.prop("selectedIndex", 0);

    //city
    let city = $("#city");
    city.empty();
    city.append('<option selected="true" disabled></option>');
    city.prop("selectedIndex", 0);

    //barangay
    let barangay = $("#barangay");
    barangay.empty();
    barangay.append('<option selected="true" disabled></option>');
    barangay.prop("selectedIndex", 0);

    // filter & fill
    var url = "ph-json/province.json";
    $.getJSON(url, function (data) {
      var result = data.filter(function (value) {
        return value.region_code == region_code;
      });

      result.sort(function (a, b) {
        return a.province_name.localeCompare(b.province_name);
      });

      $.each(result, function (key, entry) {
        dropdown.append(
          $("<option></option>").attr("value", entry.province_code).text(entry.province_name)
        );
      });
    });
  },
  // fill city
  fill_cities: function () {
    //selected province
    var province_code = $(this).val();

    // set selected text to input
    var province_text = $(this).find("option:selected").text();
    let province_input = $("#province-text");
    province_input.val(province_text);
    //clear city & barangay input
    $("#city-text").val("");
    $("#barangay-text").val("");

    //city
    let dropdown = $("#city");
    dropdown.empty();
    dropdown.append('<option selected="true" disabled>Choose city/municipality</option>');
    dropdown.prop("selectedIndex", 0);

    //barangay
    let barangay = $("#barangay");
    barangay.empty();
    barangay.append('<option selected="true" disabled></option>');
    barangay.prop("selectedIndex", 0);

    // filter & fill
    var url = "ph-json/city.json";
    $.getJSON(url, function (data) {
      var result = data.filter(function (value) {
        return value.province_code == province_code;
      });

      result.sort(function (a, b) {
        return a.city_name.localeCompare(b.city_name);
      });

      $.each(result, function (key, entry) {
        dropdown.append(
          $("<option></option>").attr("value", entry.city_code).text(entry.city_name)
        );
      });
    });
  },
  // fill barangay
  fill_barangays: function () {
    // selected barangay
    var city_code = $(this).val();

    // set selected text to input
    var city_text = $(this).find("option:selected").text();
    let city_input = $("#city-text");
    city_input.val(city_text);
    //clear barangay input
    $("#barangay-text").val("");

    // barangay
    let dropdown = $("#barangay");
    dropdown.empty();
    dropdown.append('<option selected="true" disabled>Choose barangay</option>');
    dropdown.prop("selectedIndex", 0);

    // filter & Fill
    var url = "ph-json/barangay.json";
    $.getJSON(url, function (data) {
      var result = data.filter(function (value) {
        return value.city_code == city_code;
      });

      result.sort(function (a, b) {
        return a.brgy_name.localeCompare(b.brgy_name);
      });

      $.each(result, function (key, entry) {
        dropdown.append(
          $("<option></option>").attr("value", entry.brgy_code).text(entry.brgy_name)
        );
      });
    });
  },

  onchange_barangay: function () {
    // set selected text to input
    var barangay_text = $(this).find("option:selected").text();
    let barangay_input = $("#barangay-text");
    barangay_input.val(barangay_text);
  },
};

$(function () {
  // events
  $("#region").on("change", my_handlers.fill_provinces);
  $("#province").on("change", my_handlers.fill_cities);
  $("#city").on("change", my_handlers.fill_barangays);
  $("#barangay").on("change", my_handlers.onchange_barangay);

  // load region
  let dropdown = $("#region");
  dropdown.empty();
  dropdown.append('<option selected="true" disabled>Choose Region</option>');
  dropdown.prop("selectedIndex", 0);
  const url = "ph-json/region.json";
  // Populate dropdown with list of regions
  $.getJSON(url, function (data) {
    $.each(data, function (key, entry) {
      dropdown.append(
        $("<option></option>").attr("value", entry.region_code).text(entry.region_name)
      );
    });
  });
});

$(function () {
  // Initial level and hierarchy tracking
  let level = "region";
  let locationText = "";

  // Function to update options in the single selector
  function updateOptions(data, placeholder) {
    let dropdown = $("#location-selector");
    dropdown.empty();
    dropdown.append(`<option selected="true" disabled>${placeholder}</option>`);
    $.each(data, function (key, entry) {
      dropdown.append($("<option></option>").attr("value", entry.code).text(entry.name));
    });
  }

  // Function to load the next level based on selected code
  function loadNextLevel(code, nextLevel, placeholder, jsonFile) {
    $.getJSON(jsonFile, function (data) {
      const filteredData = data.filter(function (item) {
        return item.parent_code === code;
      });
      updateOptions(filteredData, placeholder);
      level = nextLevel; // Move to the next level in the selection process
    });
  }

  // Load initial region options
  $.getJSON("ph-json/region.json", function (data) {
    const regions = data.map((region) => ({ code: region.region_code, name: region.region_name }));
    updateOptions(regions, "Choose Region");
  });

  // Handler for changing selection levels
  $("#location-selector").on("change", function () {
    const selectedCode = $(this).val();
    const selectedText = $(this).find("option:selected").text();

    if (level === "region") {
      locationText = selectedText;
      loadNextLevel(selectedCode, "province", "Choose Province", "ph-json/province.json");
    } else if (level === "province") {
      locationText += ` - ${selectedText}`;
      loadNextLevel(selectedCode, "city", "Choose City/Municipality", "ph-json/city.json");
    } else if (level === "city") {
      locationText += ` - ${selectedText}`;
      loadNextLevel(selectedCode, "barangay", "Choose Barangay", "ph-json/barangay.json");
    } else if (level === "barangay") {
      locationText += ` - ${selectedText}`;
      $("#location-text").val(locationText); // Store full location path
      $("#location-selector").prop("disabled", true); // Disable further changes
      $("#location-selector").append(`<option selected="true">${locationText}</option>`);
    }
  });
});
$(document).ready(function () {
  let level = "region";
  let locationText = "";

  // Function to load options into the dropdown
  function updateDropdown(data, placeholder) {
    let dropdown = $("#location-selector");
    dropdown.empty();
    dropdown.append(`<option selected="true" disabled>${placeholder}</option>`);
    $.each(data, function (index, item) {
      dropdown.append($("<option></option>").attr("value", item.code).text(item.name));
    });
  }

  // Function to load the next level of location data
  function loadNextLevel(code, nextLevel, placeholder, jsonFile, parentKey) {
    $.getJSON(jsonFile, function (data) {
      const filteredData = data.filter((item) => item[parentKey] === code);
      updateDropdown(filteredData, placeholder);
      level = nextLevel; // Set the next level
    }).fail(function () {
      console.error(`Failed to load JSON from ${jsonFile}`);
    });
  }

  // Load initial regions on page load
  $.getJSON("ph-json/region.json", function (data) {
    const regions = data.map((region) => ({ code: region.region_code, name: region.region_name }));
    updateDropdown(regions, "Choose Region");
  }).fail(function () {
    console.error("Failed to load regions from ph-json/region.json");
  });

  // Handle dropdown changes to load the next level
  $("#location-selector").on("change", function () {
    const selectedCode = $(this).val();
    const selectedText = $(this).find("option:selected").text();

    // Update location text and load the next level
    if (level === "region") {
      locationText = selectedText;
      loadNextLevel(
        selectedCode,
        "province",
        "Choose Province",
        "ph-json/province.json",
        "region_code"
      );
    } else if (level === "province") {
      locationText += ` - ${selectedText}`;
      loadNextLevel(
        selectedCode,
        "city",
        "Choose City/Municipality",
        "ph-json/city.json",
        "province_code"
      );
    } else if (level === "city") {
      locationText += ` - ${selectedText}`;
      loadNextLevel(
        selectedCode,
        "barangay",
        "Choose Barangay",
        "ph-json/barangay.json",
        "city_code"
      );
    } else if (level === "barangay") {
      locationText += ` - ${selectedText}`;
      $("#location-text").val(locationText); // Save full location
      $("#location-selector").prop("disabled", true); // Disable dropdown
      $("#location-selector").append(`<option selected="true">${locationText}</option>`);
    }
  });
});
