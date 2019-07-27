var number_of_ingredients, number_of_subrecipes = 1;
var temp_subrecipe_field, list_of_units;
let all_units = true;
let position = -1;

setInterval(function () {
    const images = ["header_bg_2.jpg", "header_bg_3.jpg", "header_bg_1.jpg"];
    let path = "assets/img/";
    const image = $("header.masthead");

    position++;
    if(position >= images.length){
        position = 0;
    }

    image.fadeOut('slow', function(){
        image.css("background-image", "url('"+path+images[position]+"')");
        image.fadeIn('fast');
    });
}, 60000);

$("#new-ingredient-button").click(function () {
    const url = $("#create-ingredient-link").attr("href");
    $("#process-ingredient-form").attr("action", url);

    clear_ingredient_form(url);
    clear_ingredient_validation_messeges();
    $("#process-ingredient").modal("show");
});

function clear_ingredient_form(url){
    all_units = true;

    $("#process-ingredient-form").attr("action", url);
    $("#ingredient-name").val("");
    $("#ingredient-price").val("");
    $("#ingredient-quantity").val("");
    $("select[name^=ingredient_unit]").append(list_of_units);
    $("select[name^=ingredient_unit]").val("x");

    $("#process-ingredient-title").text("Nová ingrediencia");
    $("#new-ingredient").removeClass("hidden");
    $("#close-ingredient").removeClass("hidden");
    $("#edit-ingredient").addClass("hidden");
    $("#cancel-ingredient").addClass("hidden");
}

function clear_ingredient_validation_messeges() {
    $(".recipe-error").remove();
    $("input, select").css("border-color", "gray")
}

$("#new-ingredient").click(function (e) {
    e.preventDefault();

    let form = $("#process-ingredient-form");
    let url = form.attr('action');

    $.post({
        url: url,
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data: form.serialize(),
        dataType: "json",
        success: function (data) {
            if (data.inserted) {
                location.reload();
            } else if (data.error) {
                show_ingredient_validation_errors(data);
            }
        }
    });
});

$(".delete-ingredient-button").click(function () {
    $(".item-for-delete").text($(this).attr("data-ingredient-name"));
    $("#delete-ingredient").attr("data-ingredient-delete-id", this.id);
    $("#remove-ingredient").modal("show");
});

$("#delete-ingredient").click(function (e) {
    e.preventDefault();

    $.post({
        url: $("#delete-ingredient-link").attr("href")+$(this).attr("data-ingredient-delete-id"),
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType: "json",
        success: function (data) {
            if (data.deleted) {
                location.reload();
            } else {
                show_dialog("Ľutujeme, ale pri odstránení nastala chyba!");
            }
        }
    });
});

$(".edit-ingredient-button").click(function () {
    const row = $(this).parent().parent();
    const url = $("#update-ingredient-link").attr("href");
    let price = $(row).find("td[class^=ingredient-price]").text();

    reset_units();
    load_data_in_form(row , price, url, this);
    clear_ingredient_validation_messeges();

    $("#process-ingredient").modal("show");
});

function reset_units(){
    all_units = false;
    if(list_of_units == null){
        list_of_units = $("select[name^=ingredient_unit]").children();
    }else{
        $("select[name^=ingredient_unit]").children().remove();
        $("select[name^=ingredient_unit]").append(list_of_units);
    }
}

function load_data_in_form(row, price, url, _this){
    $("#process-ingredient-form").attr("action", url);
    $("#ingredient-name").val($(row).find("td[class^=ingredient-name]").text());
    $("#ingredient-price").val(price.substr(0, price.length-1));
    $("#ingredient-quantity").val($(row).find("td[class^=ingredient-quantity]").text());
    $("select[name^=ingredient_unit]").val($(row).find("td[class^=ingredient-unit-id]").text());

    $("#edit-ingredient").attr("data-ingredient-update-id", _this.id);

    $("#process-ingredient-title").text("Zmena ingrediencie");
    $("#new-ingredient").addClass("hidden");
    $("#close-ingredient").addClass("hidden");
    $("#edit-ingredient").removeClass("hidden");
    $("#cancel-ingredient").removeClass("hidden");
}

$("#edit-ingredient").click(function (e) {
    e.preventDefault();

    let form = $("#process-ingredient-form");
    let url = form.attr('action');

    $.post({
        url: url + $(this).attr("data-ingredient-update-id"),
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data: form.serialize(),
        dataType: "json",
        success: function (data) {
            if (data.updated) {
                location.reload();
            } else if (data.error) {
                show_ingredient_validation_errors(data);
            } else {
                show_dialog("Ľutujeme, ale pri aktualizovaní ingrediencie nastala chyba!");
            }
        }
    });
});

function show_dialog(text) {
    $("#dialog-text").text(text);
    $('#info-ingredient').modal('show');
}

function show_ingredient_validation_errors(data) {
    $.each(data.validation, function(field_name, field_error){
        $("[name='"+field_name+"']").after("<small class='form-text text-muted ingredient-error'>"+field_error+"</small>");
        $("[name='"+field_name+"']").css("border-color", "red")
    });
}

$("#new-recipe-button").click(function () {
    const url = $("#create-recipe-link").attr("href");
    $("#process-recipe-subrecipe-form").attr("action", url);

    if($("#process-recipe-subrecipe-title").text() === "Zmena receptu"){
        $("#process-recipe-subrecipe-title").text("Nový recept")
    }

    if($("#process-recipe-subrecipe-title").text() === "Zmena subreceptu"){
        $("#process-recipe-subrecipe-title").text("Nový subrecept")
    }

    clear_recipe_form(url);
    clear_recipe_validation_messeges();
    $("#process-recipe-subrecipe").modal("show");
});

function clear_recipe_form(url){
    $("#process-ingredient-form").attr("action", url);
    $("#recipe-name").val("");
    $("#recipe-serving").val("");

    let ingredient_element = $("#recipe-ingredients").children().eq(0);
    ingredient_element.find("select[name^=recipe_ingredients]").val("x");
    ingredient_element.find("input[name^=ingredients_quantity]").val("");
    ingredient_element.find("select[name^=ingredients_units]").children().remove();
    ingredient_element.find("select[name^=ingredients_units]").append("<option value='x'>Zvoľte si jednotku</option>");
    ingredient_element.find("input[name^=ingredient_price]").val("");

    $("#recipe-ingredients").children().remove();
    $("#recipe-ingredients").append(ingredient_element);

    let subrecipe_element = $("#recipe-subrecipes").children().eq(0);
    subrecipe_element.find("select[name^=recipe_subrecipes]").val("x");
    subrecipe_element.find("input[name^=subrecipes_quantity]").val("");
    subrecipe_element.find("select[name^=subrecipe_units]").children().remove();
    subrecipe_element.find("select[name^=subrecipe_units]").append("<option value='x'>Zvoľte si jednotku</option>");
    subrecipe_element.find("input[name^=subrecipe_price]").val("");

    $("#recipe-subrecipes").children().remove();
    $("#recipe-subrecipes").append(subrecipe_element);


    $(".remove-ingredient-from-recipe").click(delete_ingredient_from_recipe);
    $("select[name^=recipe_ingredients]").change(load_ingredient_details);
    $("input[name^=ingredients_quantity]").keyup(track_quantiy_change);

    $("select[name^=ingredients_units]").focus(load_ingredient_units);
    $("select[name^=ingredients_units]").change(load_unit_convt_value);

    $(".remove-subrecipe-from-recipe").click(delete_subrecipe_from_recipe);
    $("select[name^=recipe_subrecipes]").change(load_subrecipe_details);
    $("input[name^=subrecipes_quantity]").keyup(track_quantiy_change);

    $("select[name^=subrecipe_units]").focus(load_subrecipe_units);
    $("select[name^=subrecipe_units]").change(load_unit_convt_value);

    $("#new-recipe").removeClass("hidden");
    $("#close-recipe").removeClass("hidden");
    $("#edit-recipe").addClass("hidden");
    $("#cancel-recipe").addClass("hidden");
}

$("#new-recipe").click(function (e) {
    e.preventDefault();
    clear_recipe_validation_messeges();

    let form = $("#process-recipe-subrecipe-form");
    let url = form.attr('action');

    $.post({
        url: url,
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data: form.serialize(),
        dataType: "json",
        success: function (data) {
            if (data.inserted) {
                location.reload();
            } else if (data.error) {
                show_recipe_validation_errors(data);
            }
        }
    });
});

function clear_recipe_validation_messeges(){
    $(".recipe-error").remove();
    $(".subrecipe-error").remove();
    $("input, select").css("border-color", "gray")
}

function show_recipe_validation_errors(data) {
   let error_messages = [];
    $.each(data.validation, function(field_name, field_error){
       if((field_name === "recipe_name") || (field_name === "recipe_serving")){
           $("[name='"+field_name+"']").after("<small class='form-text text-muted recipe-error'>"+field_error+"</small>");
       }else{
           if(error_messages.indexOf(field_error) === -1){
               if(field_name.includes("subre")){
                   $("#recipe-subrecipes-errors").append("<small class='form-text text-muted subrecipe-error'>"+field_error+"</small>");
               }else{
                   $("#recipe-ingredients-errors").append("<small class='form-text text-muted recipe-error'>"+field_error+"</small>");
               }
               error_messages.push(field_error)
           }
       }
       $("[name='"+field_name+"']").css("border-color", "red")
   });
}

$(".edit-recipe-button").click(function () {
    const row = $(this).parent().parent();
    const url = $("#update-recipe-link").attr("href");

    if($("#process-recipe-subrecipe-title").text() === "Nový recept"){
        $("#process-recipe-subrecipe-title").text("Zmena receptu")
    }

    if($("#process-recipe-subrecipe-title").text() === "Nový subrecept"){
        $("#process-recipe-subrecipe-title").text("Zmena subreceptu")
    }

    prepear_edit_form(row, url, this);
    load_ingrediens_to_recipe();
    load_subrecipes_to_recipe(this);
    clear_recipe_validation_messeges();

    $("#process-recipe-subrecipe").modal("show");
});

function prepear_edit_form(row, url, _this){
    $("#process-recipe-subrecipe-form").attr("action", url);
    $("#recipe-name").val($(row).find("td[class^=recipe-name]").text());
    $("#recipe-serving").val($(row).find("td[class^=recipe-serving]").text());
    $(".recipe_unit").val($(row).find("td[class^=recipe-unit_id]").text());

    $("#edit-recipe").attr("data-recipe-update-id", _this.id);

    $("#new-recipe").addClass("hidden");
    $("#close-recipe").addClass("hidden");
    $("#edit-recipe").removeClass("hidden");
    $("#cancel-recipe").removeClass("hidden");
}

function load_ingrediens_to_recipe(){
    const url = $("#recipe-ingredient-items-link").attr("href");
    const recipe_id = $("#edit-recipe").attr("data-recipe-update-id");

    let ingredients_field = $("#recipe-ingredients").children().eq(0).clone();
    $("#recipe-ingredients").children().remove();

    number_of_ingredients = 0;

    $.get({
        url: url + recipe_id,
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType: "json",
        success: function (data) {
            for(ingredient in data){
                ingredients_field.find("select[name^=ingredients_units]").children().remove();

                ingredients_field.find("select[name^=recipe_ingredients]").val(data[ingredient].ingredient_id);
                ingredients_field.find("input[name^=ingredients_quantity]").val(Math.floor(data[ingredient].quantity));
                ingredients_field.find("select[name^=ingredients_units]").append("<option value='"+data[ingredient].unit_id+"'>"+data[ingredient].label+"</option>");
                ingredients_field.find("select[name^=ingredients_units]").val(data[ingredient].unit_id);
                ingredients_field.find("input[name^=ingredient_price]").val(((data[ingredient].price*data[ingredient].convt)*data[ingredient].quantity).toFixed(2));

                if(data[ingredient].base_unit == null){
                    ingredients_field.find("input[name^=ingredient_base_unit]").val(data[ingredient].unit_id);
                    ingredients_field.find("input[name^=ingredient_base_unit]").attr("data-base-unit-label", data[ingredient].label);
                }else{
                    ingredients_field.find("input[name^=ingredient_base_unit]").val(data[ingredient].base_unit);
                    ingredients_field.find("input[name^=ingredient_base_unit]").attr("data-base-unit-label", data[ingredient].base_unit_label);
                }

                ingredients_field.find("input[name^=ingredient_base_price]").val(data[ingredient].price);
                ingredients_field.find("input[name^=ingredient_unit_convt]").val(data[ingredient].convt);

                ingredients_field.find("select[name^=recipe_ingredients]").attr("name", "recipe_ingredients["+number_of_ingredients+"]");
                ingredients_field.find("input[name^=ingredients_quantity]").attr("name","ingredients_quantity["+number_of_ingredients+"]");
                ingredients_field.find("select[name^=ingredients_units]").attr("name", "ingredients_units["+number_of_ingredients+"]");
                ingredients_field.find("input[name^=ingredient_price]").attr("name", "ingredient_price["+number_of_ingredients+"]");

                $("#recipe-ingredients").append(ingredients_field);

                $(".remove-ingredient-from-recipe").click(delete_ingredient_from_recipe);
                $("select[name^=recipe_ingredients]").change(load_ingredient_details);
                $("input[name^=ingredients_quantity]").keyup(track_quantiy_change);
                $("select[name^=ingredients_units]").focus(load_ingredient_units);
                $("select[name^=ingredients_units]").change(load_unit_convt_value);

                ingredients_field = ingredients_field.clone();
                number_of_ingredients++;
            }
        }
    });
}

function load_subrecipes_to_recipe(){
    const url = $("#recipe-subrecipe-items-link").attr("href");
    const recipe_id = $("#edit-recipe").attr("data-recipe-update-id");

    let subrecipe_field = $("#recipe-subrecipes").children().eq(0).clone();
    $("#recipe-subrecipes").children().remove();

    number_of_subrecipes = 0;

    $.get({
        url: url + recipe_id,
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType: "json",
        success: function (data) {
            if(data.length > 0){
                for(recipe in data) {
                    subrecipe_field.find("select[name^=subrecipe_units]").children().remove();

                    subrecipe_field.find("select[name^=recipe_subrecipes]").val(data[recipe].recipe_id);
                    subrecipe_field.find("input[name^=subrecipes_quantity]").val(Math.floor(data[recipe].quantity));
                    subrecipe_field.find("select[name^=subrecipe_units]").append("<option value='"+data[recipe].unit_id+"'>"+data[recipe].label+"</option>");
                    subrecipe_field.find("select[name^=subrecipe_units]").val(data[recipe].unit_id);
                    subrecipe_field.find("input[name^=subrecipe_price]").val(((data[recipe].price*data[recipe].convt)*data[recipe].quantity).toFixed(2));

                    if(data[recipe].base_unit == null){
                        subrecipe_field.find("input[name^=subrecipe_base_unit]").val(data[recipe].unit_id);
                        subrecipe_field.find("input[name^=subrecipe_base_unit]").attr("data-base-unit-label", data[recipe].label);
                    }else{
                        subrecipe_field.find("input[name^=subrecipe_base_unit]").val(data[recipe].base_unit);
                        subrecipe_field.find("input[name^=subrecipe_base_unit]").attr("data-base-unit-label", data[recipe].base_unit_label);
                    }
                    subrecipe_field.find("input[name^=subrecipe_base_price]").val(data[recipe].price);
                    subrecipe_field.find("input[name^=subrecipe_unit_convt]").val(data[recipe].convt);

                    subrecipe_field.find("select[name^=recipe_subrecipes]").attr("name", "recipe_subrecipes["+number_of_subrecipes+"]");
                    subrecipe_field.find("input[name^=subrecipes_quantity]").attr("name", "subrecipes_quantity["+number_of_subrecipes+"]");
                    subrecipe_field.find("select[name^=subrecipe_units]").attr("name", "subrecipe_units["+number_of_subrecipes+"]");
                    subrecipe_field.find("input[name^=subrecipe_price]").attr("name", "subrecipe_price["+number_of_subrecipes+"]");

                    $("#recipe-subrecipes").append(subrecipe_field);

                    $(".remove-subrecipe-from-recipe").click(delete_subrecipe_from_recipe);
                    $("select[name^=recipe-subrecipes]").change(load_subrecipe_details);
                    $("input[name^=subrecipes_quantity]").keyup(track_quantiy_change);
                    $("select[name^=subrecipe_units]").focus(load_subrecipe_units);
                    $("select[name^=subrecipe_units]").change(load_unit_convt_value);

                    subrecipe_field = subrecipe_field.clone()
                    number_of_subrecipes++;
                }
            }else{
                subrecipe_field.find("input[name^=subrecipes_quantity]").val("");
                subrecipe_field.find("input[name^=subrecipe_price]").val("");
                subrecipe_field.find("select[name^=subrecipe_units]").children().remove();
                subrecipe_field.find("select[name^=subrecipe_units]").append("<option value='x'>Zvoľte si jednotku</option>");

                $("#recipe-subrecipes").append(subrecipe_field);

                $(".remove-subrecipe-from-recipe").click(delete_subrecipe_from_recipe);
                $("select[name^=recipe_subrecipes]").change(load_subrecipe_details);
                $("input[name^=subrecipes_quantity]").keyup(track_quantiy_change);
                $("select[name^=subrecipe_units]").focus(load_subrecipe_units);
                $("select[name^=subrecipe_units]").change(load_unit_convt_value);
            }
        }
    });
}

$("#edit-recipe").click(function (e) {
    e.preventDefault();

    let form = $("#process-recipe-subrecipe-form");
    let url = form.attr('action');

    $.post({
        url: url + $(this).attr("data-recipe-update-id"),
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data: form.serialize(),
        dataType: "json",
        success: function (data) {
            if (data.updated) {
                location.reload();
            } else if (data.error) {
                show_recipe_validation_errors(data);
            } else {
                show_dialog("Ľutujeme, ale pri aktualizovaní ingrediencie nastala chyba!");
            }
        }
    });
});

$(".delete-recipe-button").click(function () {
    $(".item-for-delete").text($(this).attr("data-recipe-name"));
    $("#delete-recipe").attr("data-recipe-delete-id", this.id);
    $("#remove-recipe").modal("show");
});

$("#delete-recipe").click(function (e) {
    e.preventDefault();

    $.post({
        url: $("#delete-recipe-link").attr("href")+$(this).attr("data-recipe-delete-id"),
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType: "json",
        success: function (data) {
            if (data.deleted) {
                location.reload();
            } else {
                show_dialog("Ľutujeme, ale pri odstránení nastala chyba!");
            }
        }
    });
});

$("select[name^=recipe_ingredients]").change(load_ingredient_details);

function load_ingredient_details(){
    const url = $("#ingredient-details-link").attr("href");
    const ingredient_id = $(this).val();
    const _this = this;

    $.get({
        url: url + ingredient_id,
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType: "json",
        success: function (data) {
            let element = $(_this).parent();

            if(data.length === 0){
                reset_ingredient_details(element)
            }else{
                let ingredient = data[0];
                $(_this).parent().find("input[name^=ingredient_base_price]").val(parseFloat(ingredient.price));
                load_ingredients_details_in_fields(ingredient, element)
            }
        }
    });
}

function reset_ingredient_details(element){
    element.find("input[name^=ingredients_quantity]").val("");
    element.find("select[name^=ingredients_units]").children().remove();
    element.find("select[name^=ingredients_units]").append("<option value='x'>Zvoľte si jednotku</option>");
    element.find("select[name^=ingredients_units]").val("x");
    element.find("input[name^=ingredient_price]").val("");
}

function load_ingredients_details_in_fields(ingredient, element){
    element.find("input[name^=ingredients_quantity]").val(ingredient.quantity);
    element.find("select[name^=ingredients_units]").children().remove();
    element.find("select[name^=ingredients_units]").append("<option data-unit-convt='1' value='"+ingredient.unit_id+"'>"+ingredient.label+"</option>");
    element.find("select[name^=ingredients_units]").val(ingredient.unit_id);
    element.find("input[name^=ingredient_base_unit]").val(ingredient.unit_id);
    element.find("input[name^=ingredient_base_unit]").attr("data-base-unit-label", ingredient.label);
    element.find("input[name^=ingredient_price]").val(ingredient.price);
}

$("select[name^=ingredients_units]").focus(load_ingredient_units);

function load_ingredient_units(){
    const url = $("#conversion-unit-link").attr("href");
    const unit_id = $(this).parent().find("input[name^=ingredient_base_unit]").val();
    const _this = this;

    if($(_this).children().length === 1){
        $.get({
            url: url + unit_id,
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            dataType: "json",
            success: function (data) {
                $(_this).children().remove();

                $(_this).append("<option data-unit-convt='1' value='"+unit_id+"'>"+$(_this).parent().find("input[name^=ingredient_base_unit]").attr("data-base-unit-label")+"</option>");
                for (unit in data){
                    $(_this).append("<option data-unit-convt='"+data[unit].convt+"' value='"+data[unit].unit_id+"'>"+data[unit].label+"</option>");
                }
            }
        });
    }
}

$("select[name^=ingredients_units]").change(load_unit_convt_value);

function load_unit_convt_value(){
    const unit_conversion = $(this).children("option:selected").attr("data-unit-convt");

    if($(this).parent().find("input[name^=ingredient_base_price]").length === 0){
        const price = $(this).parent().find("input[name^=subrecipe_base_price]").val();
        $(this).parent().find("input[name^=subrecipe_price]").val(unit_conversion*price);
        $(this).parent().find("input[name^=subrecipe_unit_convt]").val(unit_conversion);

        if($(this).parent().find("input[name^=subrecipes_quantity]").val() !== 1){
            price_recalculation(this, $(this).parent().find("input[name^=subrecipes_quantity]").val());
        }
    }else{
        const price = $(this).parent().find("input[name^=ingredient_base_price]").val();
        $(this).parent().find("input[name^=ingredient_price]").val(unit_conversion*price);
        $(this).parent().find("input[name^=ingredient_unit_convt]").val(unit_conversion);

        if($(this).parent().find("input[name^=ingredients_quantity]").val() !== 1){
            price_recalculation(this, $(this).parent().find("input[name^=ingredients_quantity]").val());
        }
    }
}

$("input[name^=ingredients_quantity]").keyup(track_quantiy_change);

function track_quantiy_change(){
    price_recalculation(this, $(this).val());
}

function price_recalculation(_this, _price){
    const quantity = parseFloat(_price);

    if($(_this).parent().find("input[name^=ingredient_base_price]").length === 0){
        const price = $(_this).parent().find("input[name^=subrecipe_base_price]").val();
        const conversion = $(_this).parent().find("input[name^=subrecipe_unit_convt]").val();
        let final_price = quantity * price * conversion;

        if(!isNaN(final_price)){
            $(_this).parent().find("input[name^=subrecipe_price]").val(final_price.toFixed(2))
        }else{
            $(_this).parent().find("input[name^=subrecipe_price]").val(0)
        }
    }else{
        const price = $(_this).parent().find("input[name^=ingredient_base_price]").val();
        const conversion = $(_this).parent().find("input[name^=ingredient_unit_convt]").val();
        let final_price = quantity * price * conversion;

        if(!isNaN(final_price)){
            $(_this).parent().find("input[name^=ingredient_price]").val(final_price.toFixed(2))
        }else{
            $(_this).parent().find("input[name^=ingredient_price]").val(0)
        }
    }
}

$("#add-ingredient-for-recipe").click(function () {
    let new_ingredient_for_recipe = $("#recipe-ingredients").children().eq(0).clone();
    new_ingredient_for_recipe.find("input[name^=ingredients_quantity]").val("");
    new_ingredient_for_recipe.find("input[name^=ingredient_price]").val("");
    new_ingredient_for_recipe.find("select[name^=recipe_ingredients]").val("x");
    new_ingredient_for_recipe.find("select[name^=ingredients_units]").children().remove();
    new_ingredient_for_recipe.find("select[name^=ingredients_units]").append("<option value='x'>Zvoľte si jednotku</option>");

    new_ingredient_for_recipe.find("select[name^=recipe_ingredients]").attr("name", "recipe_ingredients["+number_of_ingredients+"]");
    new_ingredient_for_recipe.find("input[name^=ingredients_quantity]").attr("name","ingredients_quantity["+number_of_ingredients+"]");
    new_ingredient_for_recipe.find("select[name^=ingredients_units]").attr("name", "ingredients_units["+number_of_ingredients+"]");
    new_ingredient_for_recipe.find("input[name^=ingredient_price]").attr("name", "ingredient_price["+number_of_ingredients+"]");

    $("input, select").css("border-color", "gray");
    $("#recipe-ingredients").append(new_ingredient_for_recipe);

    $(".remove-ingredient-from-recipe").click(delete_ingredient_from_recipe);
    $("select[name^=recipe_ingredients]").change(load_ingredient_details);
    $("input[name^=ingredients_quantity]").keyup(track_quantiy_change);
    $("select[name^=ingredients_units]").focus(load_ingredient_units);
    $("select[name^=ingredients_units]").change(load_unit_convt_value);

    number_of_ingredients++;
});

$(".remove-ingredient-from-recipe").click(delete_ingredient_from_recipe);

function delete_ingredient_from_recipe(){
    if($(this).parent().parent().parent().children().length > 1){
        $(this).parent().parent().remove();
    }
}

$("select[name^=recipe_subrecipes]").change(load_subrecipe_details);

function load_subrecipe_details(){
    const url = $("#subrecipe-details-link").attr("href");
    const recipe_id = $(this).val();
    const _this = this;

    $.get({
        url: url + recipe_id,
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType: "json",
        success: function (data) {
            let element = $(_this).parent();

            if(data.length === 0){
                reset_subrecipe_details(element)
            }else{
                let subrecipe = data[0];
                $(_this).parent().find("input[name^=subrecipe_base_price]").val(parseFloat(subrecipe.price));
                load_subrecipes_details_in_fields(subrecipe, element)
            }
        }
    });
}

function reset_subrecipe_details(element){
    element.find("input[name^=subrecipes_quantity]").val("");
    element.find("select[name^=subrecipe_units]").children().remove();
    element.find("select[name^=subrecipe_units]").append("<option value='x'>Zvoľte si jednotku</option>");
    element.find("select[name^=subrecipe_units]").val("x");
    element.find("input[name^=subrecipe_price]").val("");
}

function load_subrecipes_details_in_fields(subrecipe, element){
    element.find("input[name^=subrecipes_quantity]").val(subrecipe.quantity);
    element.find("select[name^=subrecipe_units]").children().remove();
    element.find("select[name^=subrecipe_units]").append("<option data-unit-convt='1' value='"+subrecipe.unit_id+"'>"+subrecipe.label+"</option>");
    element.find("select[name^=subrecipe_units]").val(subrecipe.unit_id);
    element.find("input[name^=subrecipe_price]").val(subrecipe.price);
}

$("select[name^=subrecipe_units]").focus(load_subrecipe_units);

function load_subrecipe_units(){
    const url = $("#conversion-unit-link").attr("href");
    const unit_id = $(this).parent().find("input[name^=subrecipe_base_unit]").val();
    const _this = this;

    if($(this).children().length === 1){
        $.get({
            url: url + unit_id,
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            dataType: "json",
            success: function (data) {
                $(_this).children().remove();

                $(_this).append("<option data-unit-convt='1' value='"+unit_id+"'>"+$(_this).parent().find("input[name^=subrecipe_base_unit]").attr("data-base-unit-label")+"</option>");
                for (unit in data){
                    $(_this).append("<option data-unit-convt='"+data[unit].convt+"' value='"+data[unit].unit_id+"'>"+data[unit].label+"</option>");
                }
            }
        });
    }
}

$("select[name^=subrecipe_units]").change(load_unit_convt_value);
$("input[name^=subrecipes_quantity]").keyup(track_quantiy_change);

$("#add-subrecipe-for-recipe").click(function () {
    var new_subrecipe_for_recipe = $("#recipe-subrecipes").children().eq(0).clone();
    if(temp_subrecipe_field != null){
        new_subrecipe_for_recipe = temp_subrecipe_field;
        temp_subrecipe_field = null;
    }

    new_subrecipe_for_recipe.find("input[name^=subrecipes_quantity]").val("");
    new_subrecipe_for_recipe.find("input[name^=subrecipe_price]").val("");
    new_subrecipe_for_recipe.find("select[name^=recipe_subrecipes]").val("x");
    new_subrecipe_for_recipe.find("select[name^=subrecipe_units]").children().remove();
    new_subrecipe_for_recipe.find("select[name^=subrecipe_units]").append("<option value='x'>Zvoľte si jednotku</option>");

    new_subrecipe_for_recipe.find("select[name^=recipe_subrecipes]").attr("name", "recipe_subrecipes["+number_of_subrecipes+"]");
    new_subrecipe_for_recipe.find("input[name^=subrecipes_quantity]").attr("name", "subrecipes_quantity["+number_of_subrecipes+"]");
    new_subrecipe_for_recipe.find("select[name^=subrecipe_units]").attr("name", "subrecipe_units["+number_of_subrecipes+"]");
    new_subrecipe_for_recipe.find("input[name^=subrecipe_price]").attr("name", "subrecipe_price["+number_of_subrecipes+"]");

    $("input, select").css("border-color", "gray");
    $("#recipe-subrecipes").append(new_subrecipe_for_recipe);

    $(".remove-subrecipe-from-recipe").click(delete_subrecipe_from_recipe);
    $("select[name^=recipe_subrecipes]").change(load_subrecipe_details);
    $("input[name^=subrecipes_quantity]").keyup(track_quantiy_change);
    $("select[name^=subrecipe_units]").focus(load_subrecipe_units);
    $("select[name^=subrecipe_units]").change(load_unit_convt_value);

    number_of_subrecipes++;
});

$(".remove-subrecipe-from-recipe").click(delete_subrecipe_from_recipe);

function delete_subrecipe_from_recipe(){
    if($(this).parent().parent().parent().children().length == 1){
        temp_subrecipe_field = $(this).parent().parent();
        $(this).parent().parent().parent().append("<input type='hidden' value='"+temp_subrecipe_field.find("select[name^=recipe_subrecipes]").val()+"' name='recipe_subrecipes[]'>")
    }
    $(this).parent().parent().remove();
}

$("#ingredient-unit").focus(function () {
    const url = $("#ingredient-details-link").attr("href");
    const ingredient_id = $(this).val();
    const current_unit = $(this).children("option:selected");
    const _this = this;

    save_units(_this);

    if((ingredient_id != null) && (ingredient_id !== "x") && (!all_units)){
        $.get({
            url: url + ingredient_id,
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            dataType: "json",
            success: function (data) {
                $(_this).append(current_unit);

                for (ingredient in data) {
                    $(_this).append('<option value="' + data[ingredient].unit_id + '">' + data[ingredient].label + '</option>');
                }
            }
        });
    }else{
        $(this).append(list_of_units);
        $(this).val("x");
    }
});

function save_units(_this){
    if(list_of_units == null){
        list_of_units = $(_this).children();
    }else{
        $(_this).children().remove();
    }
}