package handler

import (
	"encoding/json"
	"io"
	"log"
	"math/rand"
	"net/http"
	"strconv"
	"strings"

	"github.com/gofiber/fiber/v2"
)

type Pokemon struct {
	ID              int    `json:"id"`
	Name            string `json:"name"`
	Nickname        string `json:"nickname"`
	NicknameChanges int    `json:"-"`
	Height          int    `json:"height"`
	Weight          int    `json:"weight"`
	Sprites         struct {
		BackDefault  string `json:"back_default"`
		FrontDefault string `json:"front_default"`
	} `json:"sprites"`
}

var Pokemons = []Pokemon{}

func CapturePokemon(c *fiber.Ctx) error {
	id, err := strconv.Atoi(c.Params("id"))
	if err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
			"message": "Invalid ID",
		})
	}

	for _, pokemon := range Pokemons {
		if pokemon.ID == id {
			return c.Status(fiber.StatusConflict).JSON(fiber.Map{
				"message": "Pokemon already captured",
			})
		}
	}

	pokeApi, err := http.Get("https://pokeapi.co/api/v2/pokemon/" + strconv.Itoa(id))
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{
			"message": "Failed to fetch data from PokeAPI",
		})
	}

	responseData, err := io.ReadAll(pokeApi.Body)
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{
			"message": "Failed to read data from PokeAPI",
		})

	}

	var newPokemon Pokemon
	err = json.Unmarshal(responseData, &newPokemon)
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{
			"message": "Failed to unmarshal data from PokeAPI",
		})
	}

	// becuase 50% chance, only 1 will be captured
	if rand.Intn(2) == 0 {
		return c.Status(fiber.StatusFailedDependency).JSON(fiber.Map{
			"message": "Failed to capture Pokemon",
		})
	}
	Pokemons = append(Pokemons, newPokemon)

	return c.Status(fiber.StatusCreated).JSON(fiber.Map{
		"message": "Pokemon added successfully",
		"data":    newPokemon,
	})
}

func MyPokemonList(c *fiber.Ctx) error {
	return c.JSON(fiber.Map{
		"message": "Success",
		"data":    Pokemons,
	})
}

func AvailablePokemonList(c *fiber.Ctx) error {
	limit := c.Query("limit", "20")

	pokeApi, err := http.Get("https://pokeapi.co/api/v2/pokemon?limit=" + limit)
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{
			"message": "Failed to fetch data from PokeAPI",
		})
	}

	responseData, err := io.ReadAll(pokeApi.Body)
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{
			"message": "Failed to read data from PokeAPI",
		})
	}

	var pokemons struct {
		Results []struct {
			ID   int    `json:"id"`
			Name string `json:"name"`
			URL  string `json:"url"`
		} `json:"results"`
	}

	err = json.Unmarshal(responseData, &pokemons)
	if err != nil {
		log.Println(err)
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{
			"message": "Failed to unmarshal data from PokeAPI",
		})
	}

	for i := range pokemons.Results {
		pokemons.Results[i].ID = extractIDFromURL(pokemons.Results[i].URL)
	}

	return c.JSON(fiber.Map{
		"message": "Success",
		"data":    pokemons.Results,
	})
}

func extractIDFromURL(url string) int {
	parts := strings.Split(strings.Trim(url, "/"), "/")
	id, _ := strconv.Atoi(parts[len(parts)-1])
	return id
}

func GetPokemonDetail(c *fiber.Ctx) error {
	id, err := strconv.Atoi(c.Params("id"))
	if err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
			"message": "Invalid ID",
		})
	}

	for _, pokemon := range Pokemons {
		if pokemon.ID == id {
			return c.JSON(fiber.Map{
				"message": "Success",
				"data":    pokemon,
			})
		}
	}

	return c.Status(fiber.StatusNotFound).JSON(fiber.Map{
		"message": "Pokemon not found",
	})
}

func UpdateNickname(c *fiber.Ctx) error {
	id, err := strconv.Atoi(c.Params("id"))
	if err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
			"message": "Invalid ID",
		})
	}

	nickname := c.Params("nickname")
	for i, pokemon := range Pokemons {
		if pokemon.ID == id {
			Pokemons[i].NicknameChanges++
			Pokemons[i].Nickname = nickname + "-" + strconv.Itoa(nextFibonacci(Pokemons[i].NicknameChanges-1))

			return c.JSON(fiber.Map{
				"message": "Success",
				"data":    Pokemons[i],
			})
		}
	}

	return c.Status(fiber.StatusNotFound).JSON(fiber.Map{
		"message": "Pokemon not found",
	})
}

func nextFibonacci(n int) int {
	if n <= 1 {
		return n
	}
	a, b := 0, 1
	for i := 2; i <= n; i++ {
		a, b = b, a+b
	}
	return b
}

func getPrimeNumber(n int) bool {
	if n <= 1 || n > 20 {
		return false
	}

	for i := 2; i*i <= n; i++ {
		if n%i == 0 {
			return false
		}
	}

	return true
}

func DeletePokemon(c *fiber.Ctx) error {
	id, err := strconv.Atoi(c.Params("id"))
	if err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
			"message": "Invalid ID",
		})
	}

	release, err := strconv.Atoi(c.Query("release", "0"))
	if err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
			"message": "Invalid Payload",
		})
	}

	isPrime := getPrimeNumber(release)
	if !isPrime {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
			"message": "Invalid Release",
		})
	}

	for i, pokemon := range Pokemons {
		if pokemon.ID == id {
			Pokemons = append(Pokemons[:i], Pokemons[i+1:]...)
			return c.JSON(fiber.Map{
				"message": "Success",
			})
		}
	}

	return c.Status(fiber.StatusNotFound).JSON(fiber.Map{
		"message": "Pokemon not found",
	})
}
