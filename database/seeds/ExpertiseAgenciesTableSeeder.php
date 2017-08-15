<?php

use Illuminate\Database\Seeder;
use App\ExpertiseAgency;

class ExpertiseAgenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeders = [

            [ 'name' => 'МВД РК', 'expertise_region_id' => 1 ],
            [ 'name' => 'ДВД города Астаны', 'expertise_region_id' => 1 ],
            [ 'name' => 'УВД района «Алматы»', 'expertise_region_id' => 1 ],
            [ 'name' => 'ДВД г.Алматы', 'expertise_region_id' => 2 ],
            [ 'name' => 'Алмалинский РУВД', 'expertise_region_id' => 2 ],
            [ 'name' => 'Ауэзовское РУВД', 'expertise_region_id' => 2 ],
            [ 'name' => 'Бостандыкское РУВД', 'expertise_region_id' => 2 ],
            [ 'name' => 'Жетысуское РУВД', 'expertise_region_id' => 2 ],
            [ 'name' => 'Медеуское РУВД', 'expertise_region_id' => 2 ],
            [ 'name' => 'Турксибское РУВД', 'expertise_region_id' => 2 ],
            [ 'name' => 'ДВД Акмолинской области', 'expertise_region_id' => 3 ],
            [ 'name' => 'УВД г.Кокшетау', 'expertise_region_id' => 3 ],
            [ 'name' => 'Аккольский РОВД', 'expertise_region_id' => 3 ],
            [ 'name' => 'Аршалынский РОВД', 'expertise_region_id' => 3 ],
            [ 'name' => 'Астраханский РОВД', 'expertise_region_id' => 3 ],
            [ 'name' => 'Атбасарский РОВД', 'expertise_region_id' => 3 ],
            [ 'name' => 'Буландинский РОВД', 'expertise_region_id' => 3 ],
            [ 'name' => 'Енбекшильдерский РОВД', 'expertise_region_id' => 3 ],
            [ 'name' => 'Ерейментауский РОВД', 'expertise_region_id' => 3 ],
            [ 'name' => 'Есильский РОВД', 'expertise_region_id' => 3 ],
            [ 'name' => 'Жаксынский РОВД', 'expertise_region_id' => 3 ],
            [ 'name' => 'Жаркаинский РОВД', 'expertise_region_id' => 3 ],
            [ 'name' => 'Зерендинский РОВД', 'expertise_region_id' => 3 ],
            [ 'name' => 'Коргалжинский РОВД', 'expertise_region_id' => 3 ],
            [ 'name' => 'Сандыктауский РОВД', 'expertise_region_id' => 3 ],
            [ 'name' => 'Целиноградский РОВД', 'expertise_region_id' => 3 ],
            [ 'name' => 'Щучинский РОВД', 'expertise_region_id' => 3 ],
            [ 'name' => 'Шортандинский РОВД', 'expertise_region_id' => 3 ],
            [ 'name' => 'ДВД Алматинской области', 'expertise_region_id' => 5 ],
            [ 'name' => 'УВД города Талдыкоргана', 'expertise_region_id' => 5 ],
            [ 'name' => 'УВД города Текели', 'expertise_region_id' => 5 ],
            [ 'name' => 'Уйгурское РОВД', 'expertise_region_id' => 5 ],
            [ 'name' => 'Панфиловский РОВД', 'expertise_region_id' => 5 ],
            [ 'name' => 'Коксуйский РОВД', 'expertise_region_id' => 5 ],
            [ 'name' => 'Аксуйскиий РОВД', 'expertise_region_id' => 5 ],
            [ 'name' => 'Алакольский РОВД', 'expertise_region_id' => 5 ],
            [ 'name' => 'Балхашский РОВД', 'expertise_region_id' => 5 ],
            [ 'name' => 'Енбекшиказахский РОВД', 'expertise_region_id' => 5 ],
            [ 'name' => 'Илинский РОВД', 'expertise_region_id' => 5 ],
            [ 'name' => 'Карасайбатырский РОВД', 'expertise_region_id' => 5 ],
            [ 'name' => 'Капшагайское УВД', 'expertise_region_id' => 5 ],
            [ 'name' => 'Жамбылский РОВД', 'expertise_region_id' => 5 ],
            [ 'name' => 'Талгарский РОВД', 'expertise_region_id' => 5 ],
            [ 'name' => 'Кербулакский РОВД', 'expertise_region_id' => 5 ],
            [ 'name' => 'Кербулакский РОВД', 'expertise_region_id' => 5 ],
            [ 'name' => 'ДВД Атырауской области', 'expertise_region_id' => 6 ],
            [ 'name' => 'ГОВД г.Атырау', 'expertise_region_id' => 6 ],
            [ 'name' => 'Исатайский РОВД', 'expertise_region_id' => 6 ],
            [ 'name' => 'Кзылкугинский РОВД', 'expertise_region_id' => 6 ],
            [ 'name' => 'Жылыойский РОВД', 'expertise_region_id' => 6 ],
            [ 'name' => 'Индерский РОВД', 'expertise_region_id' => 6 ],
            [ 'name' => 'Курмангазинский РОВД', 'expertise_region_id' => 6 ],
            [ 'name' => 'Макатайский РОВД', 'expertise_region_id' => 6 ],
            [ 'name' => 'Махамбетский РОВД', 'expertise_region_id' => 6 ],
            [ 'name' => 'ДВД Восточно-Казахстанской области', 'expertise_region_id' => 16 ],
            [ 'name' => 'УВД г.Усть-Каменогорска', 'expertise_region_id' => 16 ],
            [ 'name' => 'УВД г.Семипалатинска', 'expertise_region_id' => 16 ],
            [ 'name' => 'Абайское РОВД', 'expertise_region_id' => 16 ],
            [ 'name' => 'ОВД г.Аягоза и Аягозского района', 'expertise_region_id' => 16 ],
            [ 'name' => 'Бескарагайский РОВД', 'expertise_region_id' => 16 ],
            [ 'name' => 'Бородулихинский РОВД', 'expertise_region_id' => 16 ],
            [ 'name' => 'Глубоковский РОВД', 'expertise_region_id' => 16 ],
            [ 'name' => 'Жарминский РОВД', 'expertise_region_id' => 16 ],
            [ 'name' => 'Зыряновский РОВД', 'expertise_region_id' => 16 ],
            [ 'name' => 'Зайсанский РОВД', 'expertise_region_id' => 16 ],
            [ 'name' => 'Катон-Карагайский РОВД', 'expertise_region_id' => 16 ],
            [ 'name' => 'Кокпектинский РОВД', 'expertise_region_id' => 16 ],
            [ 'name' => 'ОП г.Курчатов', 'expertise_region_id' => 16 ],
            [ 'name' => 'Курчумский РОВД', 'expertise_region_id' => 16 ],
            [ 'name' => 'ГОВД г. Риддера', 'expertise_region_id' => 16 ],
            [ 'name' => 'Тарбагатайский РОВД', 'expertise_region_id' => 16 ],
            [ 'name' => 'Уланский РОВД', 'expertise_region_id' => 16 ],
            [ 'name' => 'Урджарск РОВД', 'expertise_region_id' => 16 ],
            [ 'name' => 'Шемонаихинский РОВД', 'expertise_region_id' => 16 ],
            [ 'name' => 'ДВД Жамбылской области', 'expertise_region_id' => 8 ],
            [ 'name' => '1-й ОП', 'expertise_region_id' => 8 ],
            [ 'name' => '2-й ОП', 'expertise_region_id' => 8 ],
            [ 'name' => '3-й ОП', 'expertise_region_id' => 8 ],
            [ 'name' => 'Байзакский РОВД', 'expertise_region_id' => 8 ],
            [ 'name' => 'Жамбылский РОВД', 'expertise_region_id' => 8 ],
            [ 'name' => 'Жуалынский РОВД', 'expertise_region_id' => 8 ],
            [ 'name' => 'Кордайский РОВД', 'expertise_region_id' => 8 ],
            [ 'name' => 'Таласский РОВД', 'expertise_region_id' => 8 ],
            [ 'name' => 'Сарысуский РОВД', 'expertise_region_id' => 8 ],
            [ 'name' => 'Рыскуловский РОВД', 'expertise_region_id' => 8 ],
            [ 'name' => 'Мойынкумский РОВД', 'expertise_region_id' => 8 ],
            [ 'name' => 'Шуский РОВД', 'expertise_region_id' => 8 ],
            [ 'name' => 'ОВД г.Шу', 'expertise_region_id' => 8 ],
            [ 'name' => 'ДВД Карагандинской области', 'expertise_region_id' => 9 ],
            [ 'name' => 'УВД Казыбекбийского района г.Караганды', 'expertise_region_id' => 9 ],
            [ 'name' => 'УВД Октябрьского района', 'expertise_region_id' => 9 ],
            [ 'name' => 'УВД г.Жезказган', 'expertise_region_id' => 9 ],
            [ 'name' => 'УВД г.Темиртау', 'expertise_region_id' => 9 ],
            [ 'name' => 'Абайское РУВД', 'expertise_region_id' => 9 ],
            [ 'name' => 'Бухаржырауский РУВД', 'expertise_region_id' => 9 ],
            [ 'name' => 'Балхашский ГОВД', 'expertise_region_id' => 9 ],
            [ 'name' => 'Каражалский ГОВД', 'expertise_region_id' => 9 ],
            [ 'name' => 'Саранский ГОВД', 'expertise_region_id' => 9 ],
            [ 'name' => 'Сатпаевский ГОВД', 'expertise_region_id' => 9 ],
            [ 'name' =>'ОВД Актогайского района', 'expertise_region_id' => 9 ],
            [ 'name' =>'ОВД Жанааркинского района', 'expertise_region_id' => 9 ],
            [ 'name' =>'ОВД Каркаралинского района', 'expertise_region_id' => 9 ],
            [ 'name' =>'ОВД Нуринского района', 'expertise_region_id' => 9 ],
            [ 'name' =>'ОВД Осакаровского района', 'expertise_region_id' => 9 ],
            [ 'name' =>'ОВД Улытауского района', 'expertise_region_id' => 9 ],
            [ 'name' =>'ОВД Шетского района', 'expertise_region_id' => 9 ],
            [ 'name' =>'ГОВД г.Рудного ГУ ОВД г. Рудного', 'expertise_region_id' => 10 ],
            [ 'name' =>'Житикаринский ГОВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'Северный ОВД г.Костаная', 'expertise_region_id' => 10 ],
            [ 'name' =>'Южный ОВД г.Костаная', 'expertise_region_id' => 10 ],
            [ 'name' =>'Лисаковский ГОВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'ГОВД г.Аркалык', 'expertise_region_id' => 10 ],
            [ 'name' =>'Алтынсаринский РОВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'Амангельдинский РОВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'Аулиекольский РОВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'Денисовский РОВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'Жангельдинский РОВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'Камыстинский РОВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'Карабалыкский РОВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'Карасуский РОВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'Костанайский РОВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'Мендыгаринский РОВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'Наурузумский РОВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'Сарыкольский РОВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'Тарановский РОВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'Узункольский РОВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'Федоровский РОВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'УССО ДВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'УПП ДВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'ПРДВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'ЦВИАРНДВД', 'expertise_region_id' => 10 ],
            [ 'name' =>'ОБПП', 'expertise_region_id' => 10 ],
            [ 'name' =>'Тупкараганское РОВД', 'expertise_region_id' => 12 ],
            [ 'name' =>'Мангистауское РОВД', 'expertise_region_id' => 12 ],
            [ 'name' =>'Каракиянское РОВД', 'expertise_region_id' => 12 ],
            [ 'name' =>'ГОВД г.Жанаозен', 'expertise_region_id' => 12 ],
            [ 'name' =>'Бейнеуский РОВД', 'expertise_region_id' => 12 ],
            [ 'name' =>'УВД г.Актау', 'expertise_region_id' => 12 ],
            [ 'name' =>'ДВД Мангистауской области', 'expertise_region_id' => 12 ],
            [ 'name' =>'ГОВД г.Экибастуза', 'expertise_region_id' => 14 ],
            [ 'name' =>'Щербактинский РОВД', 'expertise_region_id' => 14 ],
            [ 'name' =>'Успенский РОВД', 'expertise_region_id' => 14 ],
            [ 'name' =>'Павлодарский РОВД', 'expertise_region_id' => 14 ],
            [ 'name' =>'Майский РОВД', 'expertise_region_id' => 14 ],
            [ 'name' =>'Лебяжинский РОВД', 'expertise_region_id' => 14 ],
            [ 'name' =>'Качирский РОВД', 'expertise_region_id' => 14 ],
            [ 'name' =>'Иртышский РОВД', 'expertise_region_id' => 14 ],
            [ 'name' =>'Железинский РОВД', 'expertise_region_id' => 14 ],
            [ 'name' =>'Баянаульский РОВД', 'expertise_region_id' => 14 ],
            [ 'name' =>'Актогайский ГОВД', 'expertise_region_id' => 14 ],
            [ 'name' =>'ГОВД г. Аксу', 'expertise_region_id' => 14 ],
            [ 'name' =>'Южный ОВД', 'expertise_region_id' => 14 ],
            [ 'name' =>'Северный ОВД', 'expertise_region_id' => 14 ],
            [ 'name' =>'ДВД Павлодарской области', 'expertise_region_id' => 14 ],
            [ 'name' =>'ДВД Северо-Казахстанской области', 'expertise_region_id' => 15 ],
            [ 'name' =>'УВД г.Петропавловска', 'expertise_region_id' => 15 ],
            [ 'name' =>'Кызылжарский РОВД', 'expertise_region_id' => 15 ],
            [ 'name' =>'РОВД им..Жумабаева', 'expertise_region_id' => 15 ],
            [ 'name' =>'Жамбылский РОВД', 'expertise_region_id' => 15 ],
            [ 'name' =>'Мамлютский РОВД', 'expertise_region_id' => 15 ],
            [ 'name' =>'РОВД Шал акына', 'expertise_region_id' => 15 ],
            [ 'name' =>'Аккайынский РОВД', 'expertise_region_id' => 15 ],
            [ 'name' =>'Тимирязевский РОВД', 'expertise_region_id' => 15 ],
            [ 'name' =>'Акжарский РОВД', 'expertise_region_id' => 15 ],
            [ 'name' =>'Айыртауский РОВД', 'expertise_region_id' => 15 ],
            [ 'name' =>'Уализановский РОВД', 'expertise_region_id' => 15 ],
            [ 'name' =>'Тайыншинский РОВД', 'expertise_region_id' => 15 ],
            [ 'name' =>'Есильский РОВД', 'expertise_region_id' => 15 ],
            [ 'name' =>'РОВД им. Г.Мусрепова', 'expertise_region_id' => 15 ],
            [ 'name' =>'ДВД Западно - Казахстанской области', 'expertise_region_id' => 7 ],
            [ 'name' =>'УВД г.Уральска', 'expertise_region_id' => 7 ],
            [ 'name' =>'Акжайыкский РУВД', 'expertise_region_id' => 7 ],
            [ 'name' =>'Бґкейординский РУВД', 'expertise_region_id' => 7 ],
            [ 'name' =>'Борлинский РУВД', 'expertise_region_id' => 7 ],
            [ 'name' =>'Жанакалинский РУВД', 'expertise_region_id' => 7 ],
            [ 'name' =>'Жанибекский РУВД', 'expertise_region_id' => 7 ],
            [ 'name' =>'Зеленовский РУВД', 'expertise_region_id' => 7 ],
            [ 'name' =>'Казталовский РУВД', 'expertise_region_id' => 7 ],
            [ 'name' =>'Каратобинское РУВД', 'expertise_region_id' => 7 ],
            [ 'name' =>'Сырымский РУВД', 'expertise_region_id' => 7 ],
            [ 'name' =>'Таскалинский РУВД', 'expertise_region_id' => 7 ],
            [ 'name' =>'Тайпакский УВД', 'expertise_region_id' => 7 ],
            [ 'name' =>'Теректинский РУВД', 'expertise_region_id' => 7 ],
            [ 'name' =>'Шынгырлауский РУВД', 'expertise_region_id' => 7 ],
            [ 'name' =>'Акжайыкский УВД', 'expertise_region_id' => 7 ],
            [ 'name' =>'Приуралское УВД', 'expertise_region_id' => 7 ],
            [ 'name' =>'Жалпакталский УВД', 'expertise_region_id' => 7 ],
            [ 'name' =>'Мартукский РОВД', 'expertise_region_id' => 4 ],
            [ 'name' =>'Ойылский РОВД', 'expertise_region_id' => 4 ],
            [ 'name' =>'Иргизский РОВД', 'expertise_region_id' => 4 ],
            [ 'name' =>'Шалкарский РОВД', 'expertise_region_id' => 4 ],
            [ 'name' =>'Хромтауский РОВД', 'expertise_region_id' => 4 ],
            [ 'name' =>'Темирское РОВД', 'expertise_region_id' => 4 ],
            [ 'name' =>'Мугалжарское РОВД', 'expertise_region_id' => 4 ],
            [ 'name' =>'Каргалинский РОВД', 'expertise_region_id' => 4 ],
            [ 'name' =>'Байганинский РОВД', 'expertise_region_id' => 4 ],
            [ 'name' =>'Алгинский РОВД', 'expertise_region_id' => 4 ],
            [ 'name' =>'Айтекебийский РОВД', 'expertise_region_id' => 4 ],
            [ 'name' =>'Кобдинский РУВД', 'expertise_region_id' => 4 ],
            [ 'name' =>'Саздинский ОП', 'expertise_region_id' => 4 ],
            [ 'name' =>'ОП г. Заречный', 'expertise_region_id' => 4 ],
            [ 'name' =>'ОВД города Актобе', 'expertise_region_id' => 4 ],
            [ 'name' =>'ДВД Актюбинская область', 'expertise_region_id' => 4 ],
            [ 'name' =>'ДВД Кызылординской области', 'expertise_region_id' => 11 ],
            [ 'name' =>'Аралский РОВД', 'expertise_region_id' => 11 ],
            [ 'name' =>'Казалинский РОВД', 'expertise_region_id' => 11 ],
            [ 'name' =>'Кармакшинский РОВД', 'expertise_region_id' => 11 ],
            [ 'name' =>'Жалагашский РОВД', 'expertise_region_id' => 11 ],
            [ 'name' =>'Сырдаринский РОВД', 'expertise_region_id' => 11 ],
            [ 'name' =>'Шиелинский РОВД', 'expertise_region_id' => 11 ],
            [ 'name' =>'Жанакорганский РОВД', 'expertise_region_id' => 11 ],
            [ 'name' =>'ДВД Южно-Казахстанской области', 'expertise_region_id' => 13 ],
            [ 'name' =>'Абайский РОВД', 'expertise_region_id' => 13 ],
            [ 'name' =>'Аль-Фарабиский РОВД', 'expertise_region_id' => 13 ],
            [ 'name' =>'Енбекшинский РОВД', 'expertise_region_id' => 13 ],
            [ 'name' =>'Арысскиий РОВД', 'expertise_region_id' => 13 ],
            [ 'name' =>'Байдибекский РОВД', 'expertise_region_id' => 13 ],
            [ 'name' =>'Кентауское УВД', 'expertise_region_id' => 13 ],
            [ 'name' =>'Махтаралский РОВД', 'expertise_region_id' => 13 ],
            [ 'name' =>'отдел полиции №1', 'expertise_region_id' => 13 ],
            [ 'name' =>'отдел полиции №2', 'expertise_region_id' => 13 ],
            [ 'name' =>'Ордабасинский РОВД', 'expertise_region_id' => 13 ],
            [ 'name' =>'Отырарский РУВД', 'expertise_region_id' => 13 ],
            [ 'name' =>'Сайрамский РОВД', 'expertise_region_id' => 13 ],
            [ 'name' =>'Созакский РОВД', 'expertise_region_id' => 13 ],
            [ 'name' =>'Сарыагашский УВД', 'expertise_region_id' => 13 ],
            [ 'name' =>'Толебиский РОВД', 'expertise_region_id' => 13 ],
            [ 'name' =>'Тулкибаский РОВД', 'expertise_region_id' => 13 ],
            [ 'name' =>'Туркистанский УВД', 'expertise_region_id' => 13 ],
            [ 'name' =>'Казыгуртский РОВД', 'expertise_region_id' => 13 ],
            [ 'name' =>'Шардаринский РОВД', 'expertise_region_id' => 13 ],
            [ 'name' =>'Юго-Восточное ДВД на транспорте', 'expertise_region_id' => 2 ],
            [ 'name' =>'ЛОВД на ст.Луговой', 'expertise_region_id' => 2 ],
            [ 'name' =>'ЛОВД на ст. Арыс', 'expertise_region_id' => 2 ],
            [ 'name' =>'ЛОВД на ст. Шымкент', 'expertise_region_id' => 2 ],
            [ 'name' =>'ЛОВД на ст. Жамбыл', 'expertise_region_id' => 2 ],
            [ 'name' =>'ЛОВД на ст.Семипалатинск', 'expertise_region_id' => 2 ],
            [ 'name' =>'ЛОВД на ст.Шу', 'expertise_region_id' => 2 ],
            [ 'name' =>'ЛОВД на ст. Защита', 'expertise_region_id' => 2 ],
            [ 'name' =>'ЛОВД на ст.Уштобе', 'expertise_region_id' => 2 ],
            [ 'name' =>'ЛОВД на ст.Алматы-1', 'expertise_region_id' => 2 ],
            [ 'name' =>'ЛОВД аэропорта г. Алматы', 'expertise_region_id' => 2 ],
            [ 'name' =>'ЛОВД на ст.Алматы-2', 'expertise_region_id' => 2 ],
            [ 'name' =>'ДВД на транспорте Западно-Казахстанской области', 'expertise_region_id' => 4 ],
            [ 'name' =>'ЛОВД на ст. Кызылорда', 'expertise_region_id' => 4 ],
            [ 'name' =>'ЛОВД на ст. Кандыагаш', 'expertise_region_id' => 4 ],
            [ 'name' =>'ЛОВД на ст. Актобе', 'expertise_region_id' => 4 ],
            [ 'name' =>'ЛОВД на ст. Уральск', 'expertise_region_id' => 4 ],
            [ 'name' =>'ЛОВД на ст. Атырау', 'expertise_region_id' => 4 ],
            [ 'name' =>'ЛОВД на ст.Актау', 'expertise_region_id' => 4 ],
            [ 'name' =>'УВД района «Сарыарка»', 'expertise_region_id' => 1 ],
            [ 'name' =>'Центральный ДВД на транспорте', 'expertise_region_id' => 1 ],
            [ 'name' =>'ЛОВД на ст.Астана', 'expertise_region_id' => 1 ],
            [ 'name' =>'ЛОВД в а\\п Астана', 'expertise_region_id' => 1 ],
            [ 'name' =>'ЛОВД на ст.Павлодар', 'expertise_region_id' => 1 ],
            [ 'name' =>'ЛОВД на ст.Караганда-Сортировочная', 'expertise_region_id' => 1 ],
            [ 'name' =>'ЛОВД на ст.Кокшетау', 'expertise_region_id' => 1 ],
            [ 'name' =>'ЛОВД на ст.Петропавловск', 'expertise_region_id' => 1 ],
            [ 'name' =>'ЛОВД на ст.Костанай', 'expertise_region_id' => 1]
        ];

        foreach ($seeders as $seeder) {
            $newSeeder = new ExpertiseAgency();

            foreach ($seeder as $row => $value) $newSeeder[$row] = $value;

            $newSeeder->save();
        }
    }
}
